<?php

namespace App\Http\Controllers\ChatController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\IOFactory;

class ChatControllerUpload extends Controller
{
    public function __invoke(Request $request)
    {
        $file = $request->file('file');

        $phpWord = IOFactory::load($file->getRealPath());

        // 获取所有文本
        $text = [];
        foreach ($phpWord->getSections() as $i => $section) {
            foreach ($section->getElements() as $j => $element) {
                if (method_exists($element, 'getText')) {
                    if ($i == 0 && $j == 0) {
                        $s = '# ' . $element->getText();
                    } else {
                        $s = $element->getText();
                    }
                    $text[] = $s;
                }
            }
        }

        $text[] = '以上内容为模板内容，请以该模板内容，并结合我咨询过的问题，生成一份新的内容';

        return $this->success([
            'content' => implode(PHP_EOL, $text)
        ]);
    }
}
