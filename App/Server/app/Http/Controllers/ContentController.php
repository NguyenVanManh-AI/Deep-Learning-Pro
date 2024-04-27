<?php

namespace App\Http\Controllers;

use App\Http\Requests\RequestAddContent;
use App\Http\Requests\RequestChangeIsDeleteContent;
use App\Http\Requests\RequestChangeIsDeleteManyContent;
use App\Http\Requests\RequestUpdateContent;
use App\Services\ContentService;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    protected ContentService $contentService;

    public function __construct(ContentService $contentService)
    {
        $this->contentService = $contentService;
    }

    public function addContent(RequestAddContent $request)
    {
        return $this->contentService->addContent($request);
    }

    public function updateContent(RequestUpdateContent $request, $id_content)
    {
        return $this->contentService->updateContent($request, $id_content);
    }

    public function contentDetail(Request $request, $id_content)
    {
        return $this->contentService->contentDetail($request, $id_content);
    }

    public function getContents(Request $request)
    {
        return $this->contentService->getContents($request);
    }

    public function getContentForBroadcast(Request $request)
    {
        return $this->contentService->getContentForBroadcast($request);
    }

    public function changeIsDeleteContent(RequestChangeIsDeleteContent $request, $id_content)
    {
        return $this->contentService->changeIsDeleteContent($request, $id_content);
    }

    public function changeIsDeleteManyContent(RequestChangeIsDeleteManyContent $request)
    {
        return $this->contentService->changeIsDeleteManyContent($request);
    }
}
