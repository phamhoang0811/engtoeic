<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\ValidationException;

abstract class Controller
{
    protected function rejectFailedImageUpload(Request $request): void
    {
        $image = $request->file('image');

        if (! $image instanceof UploadedFile || $image->isValid()) {
            return;
        }

        $message = match ($image->getError()) {
            UPLOAD_ERR_INI_SIZE => 'Ảnh vượt quá giới hạn 10 MB của máy chủ.',
            UPLOAD_ERR_FORM_SIZE => 'Ảnh vượt quá dung lượng cho phép của biểu mẫu.',
            UPLOAD_ERR_PARTIAL => 'Ảnh chỉ được tải lên một phần. Hãy thử lại.',
            UPLOAD_ERR_NO_TMP_DIR => 'Máy chủ chưa cấu hình thư mục tạm để nhận ảnh.',
            UPLOAD_ERR_CANT_WRITE => 'Máy chủ không ghi được file ảnh tạm. Hãy kiểm tra thư mục tạm của PHP.',
            UPLOAD_ERR_EXTENSION => 'Một tiện ích PHP đã chặn upload ảnh.',
            default => 'Upload ảnh thất bại (mã lỗi PHP '.$image->getError().').',
        };

        throw ValidationException::withMessages(['image' => $message]);
    }
}
