<?php

/*
 * Copyright (c) 2019 Michel Petit <petit.michel@gmail.com>
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
 * LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 * WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

namespace Malenki\SqlBookmark;

use ArrayIterator;
use InvalidArgumentException;
use Malenki\SqlBookmark\Parser\FileParser;
use SplFileInfo;
use RuntimeException;

class SqlBookmarkSet extends ArrayIterator
{
    protected $name;
    protected $path;

    public static function load(string $name, string $path) : SqlBookmarkSet
    {
        $file = new SplFileInfo($path);

        if (!$file->isReadable() && !$file->isFile()) {
            throw new RuntimeException(
                sprintf(
                    'File %s is not a file or is not readable.',
                    $file->getRealPath()
                )
            );
        }

        $fp = new FileParser($file);
        
        return $fp->run()->getAll($name);
    }

    public function setName(string $name) : SqlBookmarkSet
    {
        if (!preg_match('/^[a-zA-Z_0-9]+$/', $name)) {
            throw new InvalidArgumentException(
                'Name must have letters, digits and/or underscores'
            );
        }

        $this->name = $name;

        return $this;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function setPath($path) : SqlBookmarkSet
    {
        $this->path = $path;

        return $this;
    }

    public function getPath() : string
    {
        return $this->path;
    }
}