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

namespace Malenki\SqlBookmark\Parser;

use SplFileObject;
use Malenki\SqlBookmark\Parser\BookmarkDeclarationParser;
use Malenki\SqlBookmark\Parser\DescriptionDeclarationParser;
use Malenki\SqlBookmark\Parser\SqlDeclarationParser;
use Malenki\SqlBookmark\SqlBookmark;
use Malenki\SqlBookmark\BookmarkDeclaration;
use Malenki\SqlBookmark\DescriptionDeclaration;
use Malenki\SqlBookmark\SqlDeclaration;

class LineParser
{
    protected $n;
    protected $cursor;
    protected $nameParser;
    protected $sqlParser;

    public function __construct(SplFileObject $fileCursor)
    {
        $this->nameParser = new BookmarkDeclarationParser($fileCursor->current());
        $this->descParser = new DescriptionDeclarationParser($fileCursor->current());
        $this->sqlParser  = new SqlDeclarationParser($fileCursor->current());
        $this->cursor     = $fileCursor;
    }

    public function isName() : bool
    {
        return $this->nameParser->valid();
    }

    public function isDescription() : bool
    {
        return $this->descParser->valid();
    }

    public function isSql() : bool
    {
        return $this->sqlParser->valid();
    }

    public function getLine() : int
    {
        if (!isset($this->n)) {
            $this->n = $this->cursor->key() + 1;
        }

        return $this->n;
    }

    public function getName() : BookmarkDeclaration
    {
        $name = $this->nameParser->get();
        $name->setLine($this->getLine());

        return $name;
    }

    public function getDescription() : DescriptionDeclaration
    {
        $name = $this->descParser->get();
        $name->setLine($this->getLine());

        return $name;
    }

    public function getSql() : SqlDeclaration
    {
        $sql = $this->sqlParser->get();
        $sql->setStartLine($this->getLine());
        $sql->setEndLine($this->getLine());

        return $sql;
    }
}