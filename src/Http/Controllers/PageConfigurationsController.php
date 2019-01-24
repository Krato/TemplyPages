<?php

namespace Infinety\TemplyPages\Http\Controllers;

use App\Models\Tenant\Page;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class PageConfigurationsController extends Controller
{
    /**
     * @var mixed
     */
    protected $configurationGroup;

    public function __construct()
    {
        $modelConfig = config('temply.model_group_templates');

        $this->configurationGroup = new $modelConfig();
    }

    /**
     * @param Request $request
     */
    public function getConfigurations(Request $request)
    {
        if ($request->has('page')) {
            $page = Page::find($request->get('page'));

            if ($page) {
                $group = $page->template->group;

                if ($group) {
                    return response()->json([
                        'page'     => $page,
                        'isCustom' => $page->isCustom,
                        'group'    => $group,
                        'current'  => $page->template_type,
                    ]);
                } else {
                    return response()->json([
                        'page'     => $page,
                        'isCustom' => $page->isCustom,
                    ]);
                }
            }
        }

        return response()->json([
            'success' => false,
            'error'   => true,
        ]);
    }
}
