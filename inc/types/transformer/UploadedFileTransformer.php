<?php

namespace Ajaxy\Forms\Inc\Types\Transformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\File\UploadedFile;


class UploadedFileTransformer implements DataTransformerInterface
{
    /**
     * {@inheritdoc}
     *
     * @param array $data The array to transform to an uploaded file.
     *
     * @return \Symfony\Component\HttpFoundation\File\UploadedFile|null The
     *         uploaded file or `null` if no file has been uploaded.
     */
    public function reverseTransform($data)
    {
        if (!$data) {
            return null;
        }

        if($data instanceof UploadedFile) {
            return $data;
        }

        $path = $data['tmp_name'];
        try {
            $uploadedFile = new UploadedFile(
                $path,
                $data['name'],
                $data['type'],
                $data['error']
            );
        } catch (FileNotFoundException $ex) {
            throw new TransformationFailedException($ex->getMessage());
        }

        return $uploadedFile;
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
    public function transform($file)
    {
        return $file;
    }
}