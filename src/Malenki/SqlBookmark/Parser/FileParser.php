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

use SplFileInfo;
use Malenki\SqlBookmark\Parser\LineParser;
use Malenki\SqlBookmark\SqlBookmark;
use Malenki\SqlBookmark\SqlBookmarkSet;

class FileParser
{
    protected $file;
    protected $results;

    public function __construct(SplFileInfo $sqlFile)
    {
        $this->file    = $sqlFile;
        $this->results = new SqlBookmarkSet();
    }

    public function run() : FileParser
    {
        $file     = $this->file->openFile();
        $bookmark = null;

        foreach ($file as $idx => $line) {
            $rp = new LineParser($file);

            if ($rp->isName()) {
                $this->appendBookmark($bookmark);
                $bookmark = new SqlBookmark($rp->getName());
                $bookmark->setFile($this->file);
            }

            if ($rp->isDescription() && isset($bookmark)) {
                $bookmark->setDescription($rp->getDescription());
            }

            if ($rp->isSql() && isset($bookmark)) {
                $bookmark->appendSql($rp->getSql());
            }

            if ($file->eof()) {
                $this->appendBookmark($bookmark);
            }
        }

        return $this;
    }

    public function getAll(string $name) : SqlBookmarkSet
    {
        return $this->results->setName($name);
    }

    protected function appendBookmark(SqlBookmark $bookmark = null) : void
    {
        if (!$bookmark) {
            return;
        }

        $this->results->offsetSet(
            (string) $bookmark->getName(),
            $bookmark
        );
    }
}