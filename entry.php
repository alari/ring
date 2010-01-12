<?php
Header("Content-type: text/html; charset=utf-8");
const O_DOC_ROOT = __DIR__;
require 'phar://'.O_DOC_ROOT.'/O/o.src.phar';
//require './O/src/EntryPoint.phps';
O_EntryPoint::processRequest();