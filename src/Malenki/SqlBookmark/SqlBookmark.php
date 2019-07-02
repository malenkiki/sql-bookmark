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

use Malenki\SqlBookmark\BookmarkDeclaration;
use Malenki\SqlBookmark\DescriptionDeclaration;
use Malenki\SqlBookmark\SqlDeclaration;
use SplFileInfo;

class SqlBookmark
{
    protected $file;
    protected $name;
    protected $description;
    protected $sql;

    public function __construct(BookmarkDeclaration $name)
    {
        $this->name = $name;
    }

    public function appendSql(SqlDeclaration $sql) : SqlBookmark
    {
        if (!isset($this->sql)) {
            $this->sql = $sql;
        } else {
            $new = new SqlDeclaration($this->sql . ' ' . $sql);
            $new->setStartLine($this->sql->getStartLine());
            $new->setEndLine($sql->getEndLine());
            $this->sql = $new;
        }

        return $this;
    }

    public function hasDescription() : bool
    {
        return isset($this->description);
    }

    public function getDescription() : DescriptionDeclaration
    {
        return $this->description;
    }

    public function getFile() : SplFileInfo
    {
        return $this->file;
    }

    public function getName() : BookmarkDeclaration
    {
        return $this->name;
    }

    public function getSql() : SqlDeclaration
    {
        $out = '';

        if (isset($this->sql)) {
            $out = $this->sql;
        }

        return $out;
    }

    public function setFile(SplFileInfo $file) : SqlBookmark 
    {
        $this->file = $file;

        return $this;
    }

    public function setDescription(DescriptionDeclaration $description) : SqlBookmark
    {
        $this->description = $description;

        return $this;
    }

    public function __toString() : string
    {
        return (string) $this->getSql();
    }
}