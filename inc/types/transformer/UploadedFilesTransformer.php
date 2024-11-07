<?php

namespace Ajaxy\Forms\Inc\Types\Transformer;

use Isolated\Symfony\Component\Form\DataTransformerInterface;
use Isolated\Symfony\Component\Form\Exception\TransformationFailedException;
use Isolated\Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;
use Isolated\Symfony\Component\HttpFoundation\File\UploadedFile;


class UploadedFilesTransformer implements DataTransformerInterface
{
    /**
     * {@inheritdoc}
     *
     * @param array $data The array to transform to an uploaded file.
     *
     * @return [\Symfony\Component\HttpFoundation\File\UploadedFile|null] The
     *         uploaded file or `null` if no file has been uploaded.
     */
    public function reverseTransform($files)
    {
        if (!$files || empty($files)) {
            return null;
        }

        $uploadedFiles = [];
        foreach ($files as $key => $data) {
            $path = $data['tmp_name'];
            try {
                $uploadedFiles[] = new UploadedFile(
                    $path,
                    $data['name'],
                    $data['type'],
                    $data['error']
                );
            } catch (FileNotFoundException $ex) {
                throw new TransformationFailedException($ex->getMessage());
            }
        }

        return $uploadedFiles;
    }

    /**
     * {@inheritdoc}
     *
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile|null $file The
     *        uploaded file to transform to an `array`.
     *
     * @return \Symfony\Component\HttpFoundation\File\UploadedFile|null The
     *         argument `$file`.
     */
    public function transform($files)
    {
        return $files;
    }
}
