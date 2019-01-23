<?php

namespace Infinety\TemplyPages\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Infinety\TemplyPages\Http\Services\FieldsService;
use Infinety\TemplyPages\Http\Traits\FieldsTrait;
use Infinety\TemplyPages\Models\Page;
use Infinety\TemplyPages\Models\Template;

class PageAdvancedController extends Controller
{
    use FieldsTrait;

    /**
     * @var mixed
     */
    protected $service;

    /**
     * @param FieldsService $service
     */
    public function __construct(FieldsService $service)
    {
        $this->service = $service;
    }

    public function allPages()
    {
        return Page::all();
    }

    /**
     * @param $pageId
     * @return mixed
     */
    public function pageInfo($pageId)
    {
        $page = Page::find($pageId);
        if (!$page) {
            return response()->json([
                'success' => false,
            ]);
        }

        return $page->toJson();
    }

    /**
     * @param Request $request
     */
    public function setDesign(Request $request)
    {
        if ($request->has('pageId') && $request->has('design')) {
            $page = Page::find($request->get('pageId'));
            if ($page) {
                $page->template_type = $request->get('design');
                $page->save();

                return response()->json([
                    'success' => true,
                ]);
            }
        }

        return response()->json([
            'success' => false,
            'error'   => true,
        ]);
    }

    /**
     * @return mixed
     */
    public function fields(Request $request)
    {
        return $this->service->getFields($request->resourceId);
    }

    /**
     * @param Request $request
     */
    public function saveFields(Request $request)
    {
        $template = Template::find($request->resourceId);
        $template->fields()->delete();
        $result = $this->saveFieldsItems($template, $request->fields);
    }

    /**
     * Get Template fields for page
     *
     * @param   Request  $request
     *
     * @return  array
     */
    public function pageTemplateFields(Request $request)
    {
        $page = Page::find($request->resourceId);

        if ($page->data) {
            $data = json_decode($page->data, true);
        }

        $template = Template::find($request->templateId);

        return response()->json($template->getFieldsToPage($data ?? []));
    }

    /**
     * @param Page $page
     */
    public function pageTemplateFieldsValues(Request $request)
    {
        $page = Page::find($request->resourceId);
        $data = json_decode($page->data, true);

        $template = Template::find($page->template->id);

        return response()->json($template->getFieldsToPageWithValues($data ?? []));
    }

    /**
     * Go To
     *
     * @param   [type]  $pageId  [description]
     *
     * @return  [type]           [description]
     */
    public function gotoPage($pageId)
    {
        $page = Page::find($pageId);
        if (!$page) {
            return redirect()->back();
        }

        dump($page);
    }

    /**
     * @param Page $page
     */
    public function destroy($pageId)
    {
        $page = Page::find($pageId);

        if ($page) {
            $page->delete();

            return response()->json([
                'success' => true,
            ]);
        }

        return response()->json([
            'success' => false,
            'error'   => true,
        ]);
    }
}
