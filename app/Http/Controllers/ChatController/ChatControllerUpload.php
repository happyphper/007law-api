<?php

namespace App\Http\Controllers\ChatController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\Element\ListItemRun;
use PhpOffice\PhpWord\Element\Text;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\IOFactory;

class ChatControllerUpload extends Controller
{
    public function __invoke(Request $request)
    {
        $file = $request->file('file');

        $phpWord = IOFactory::load($file->getRealPath());

//        $file = storage_path('app/111.docx');

        $phpWord = IOFactory::load($file);

        // 获取所有文本
        $text = '';
        foreach ($phpWord->getSections() as $i => $section) {
            $paragraph = '';
            foreach ($section->getElements() as $j => $element) {
                if ($i === 0 && $j === 0) {
                    if (method_exists($element, 'getText')) {
                        $s = $element->getText();
                        $paragraph .= $s . PHP_EOL;
                    }
                } else if ($element instanceof ListItemRun) {
                    // 段落开始
                    $paragraph .= $element->getText() . PHP_EOL;
                } else if ($element instanceof Text || $element instanceof TextRun) {
                    $paragraph .= $element->getText();
                }
            }
            $text .= $paragraph . PHP_EOL;
        }

        $text .= '以上内容为模板内容，请以该模板内容，并结合我咨询过的问题，生成一份新的内容';

        return $this->success(['content' => $text]);
    }
}
