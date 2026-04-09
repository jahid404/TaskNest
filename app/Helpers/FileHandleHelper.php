<?php

/**
 * Get file path
 */
if (!function_exists('get_file_path')) {
    function get_file_path($file)
    {
        $path = parse_url($file, PHP_URL_PATH);
        return ltrim($path, '/');
    }
}

/**
 * Handle delete file
 */
if (!function_exists('handleUploadFile')) {
    function handleUploadFile($file, $dir, $hasOldFile = false, $oldFilePath = null)
    {
        if ($file && is_file($file)) {
            $filename = time() . '-' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $dir . '/' . $filename;
            $file->move(public_path($dir), $filename);

            // is has old file, then unlink
            $oldFilePath = get_file_path($oldFilePath);
            if (is_string($oldFilePath) && $hasOldFile && $oldFilePath && file_exists(public_path($oldFilePath))) {
                unlink(public_path($oldFilePath));
            }
            return $path;
        }

        return null;
    }
}

/**
 * Handle delete file
 */
if (!function_exists('handleDeleteFile')) {
    function handleDeleteFile($dir)
    {
        $dir = get_file_path($dir);
        if ($dir && file_exists(public_path($dir))) {
            unlink(public_path($dir));

            return true;
        }
        return false;
    }
}
