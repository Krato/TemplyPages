<?php

namespace Infinety\TemplyPages\Http\Controllers;

use App\Models\System\GroupTemplates;
use App\Models\Tenant\Page;
use App\Models\Tenant\Template;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Infinety\TemplyPages\Http\Services\FieldsService;
use Infinety\TemplyPages\Http\Traits\FieldsTrait;

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

        if (!$page) {
            return null;
        }

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
     * @param $template
     * @return mixed
     */
    public function getPageTemplatesTypes(Template $template)
    {
        if ($template->group_id == 1) {
            $groups = GroupTemplates::whereIn('id', [9, 10, 11, 12])->get();
        } else {
            $groups = GroupTemplates::where('id', $template->group_id)->get();
        }

        if (count($groups) > 0) {
            $list = [];
            foreach ($groups as $group) {
                $templates = $group->templates;

                foreach ($templates as $templateData) {
                    $list[] = [
                        'value'   => $templateData->id,
                        'display' => trim(preg_replace('/\d+/u', '', $templateData->name)),
                    ];
                }
            }

            return $list;
        }
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
