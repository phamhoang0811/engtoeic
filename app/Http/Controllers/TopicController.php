<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class TopicController extends Controller
{
    // ================= DÀNH CHO HỌC VIÊN =================
    public function index(Request $request)
    {
        $score = $request->integer('score', 450);
        $topics = Topic::query()
            ->where('target_score', $score)
            ->orderBy('id')
            ->paginate(12)
            ->withQueryString();

        return view('topics', compact('topics', 'score'));
    }

    // ================= DÀNH CHO ADMIN =================
    public function adminIndex(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $filters = $request->validate([
            'search' => 'nullable|string|max:255',
            'target_score' => 'nullable|integer|in:450,650,990',
        ]);

        $topicsQuery = Topic::query()->orderBy('id');

        if (! empty($filters['search'])) {
            $topicsQuery->where('name', 'like', '%'.trim($filters['search']).'%');
        }

        if (! empty($filters['target_score'])) {
            $topicsQuery->where('target_score', $filters['target_score']);
        }

        $topics = $topicsQuery->get();

        return view('admin.topics.index', compact('topics', 'filters'));
    }

    public function create()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        return view('admin.topics.create');
    }

    public function edit($id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $topic = Topic::findOrFail($id);

        return view('admin.topics.edit', compact('topic'));
    }

    public function store(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $this->rejectFailedImageUpload($request);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'target_score' => 'required|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'description' => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('topics', 'public');
            if ($imagePath === false) {
                throw new RuntimeException('Không thể lưu ảnh chủ đề.');
            }

            $validated['image'] = $imagePath;
        }

        Topic::create($validated);

        return redirect('/admin/topics')->with('success', 'Thêm chủ đề thành công!');
    }

    public function update(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $this->rejectFailedImageUpload($request);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'target_score' => 'required|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'remove_image' => 'nullable|boolean',
            'description' => 'nullable|string|max:255',
        ]);

        $topic = Topic::findOrFail($id);
        $previousImage = $topic->image;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('topics', 'public');
            if ($imagePath === false) {
                throw new RuntimeException('Không thể lưu ảnh chủ đề.');
            }

            $validated['image'] = $imagePath;
        } elseif ($request->boolean('remove_image')) {
            $validated['image'] = null;
        } else {
            unset($validated['image']);
        }

        unset($validated['remove_image']);
        $topic->update($validated);

        if ($previousImage && $topic->image !== $previousImage) {
            Storage::disk('public')->delete($previousImage);
        }

        return redirect('/admin/topics')->with('success', 'Cập nhật chủ đề thành công!');
    }

    public function destroy($id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $topic = Topic::findOrFail($id);
        $topic->delete();
        if ($topic->image) {
            Storage::disk('public')->delete($topic->image);
        }

        return redirect('/admin/topics')->with('success', 'Đã xóa chủ đề!');
    }
}
