<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PermalinkController extends IndexController
{
    /**
     * Map permalink to controller action.
     */
    public function route(Request $request, $type, $slug, $subType = null, $subSlug = null)
    {
        $routeData = [
            'type' => $type,
            'slug' => $slug,
            'subType' => $subType,
            'subSlug'=> $subSlug,
        ];

        $permalink = \App\Permalink::where(['entity_type' => $type, 'slug' => $slug])->first();

        if (!$permalink) {
            return $this->errorPage404($request, 'Permalink is missing', $routeData);
        }

        $subPermalink = \App\Permalink::where(['entity_type' => $subType, 'slug' => $subSlug])->first();

        $action = $this->getAction($permalink, $subPermalink);

        if (method_exists($this, $action)) {
            $params = [$request, $permalink->entity_id];

            if ($subPermalink) {
                $params[] = $subPermalink->entity_id;
            }

            return call_user_func_array([$this, $action], $params);
        }

        return $this->errorPage404($request, 'Controller method doesn\'t exist', $routeData);
    }

    /**
     * Helper function that returns a contoller action method based on url.
     *
     * @param String $entityType
     * @param String $page
     * @return String Controller action
     */
    private function getAction($permalink, $subPermalink)
    {
        $baseAction = strtolower($permalink->entity_type);

        $subAction = '';
        if ($subPermalink) {
            $subAction = ucfirst(strtolower($subPermalink->entity_type));
        }

        return $baseAction . $subAction;
    }

    /**
     * 404 handler.
     *
     * @param string $message
     * @param array $data
     * @return
     */
    private function errorPage404($request, $message, $data)
    {
        $error = $message . ' for ' . $request->fullUrl() . "\n";
        $error .= 'Referer: ' . url()->previous() . "\n";

        \App\Helpers\SlackHelper::message('error', $error);

        return view('public/404');
    }
}
