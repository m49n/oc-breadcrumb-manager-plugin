<?php namespace Dubk0ff\BreadcrumbManager\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Dubk0ff\BreadcrumbManager\Models\Template as TemplateModel;
use Flash;
use Lang;
use Redirect;
use System\Classes\SettingsManager;

/**
 * Class Templates
 * @package Dubk0ff\BreadcrumbManager\Controllers
 */
class Templates extends Controller
{
    /** @var array  */
    public $implement = [
        \Backend\Behaviors\FormController::class,
        \Backend\Behaviors\ListController::class
    ];

    /** @var string  */
    public $formConfig = 'config_form.yaml';

    /** @var string  */
    public $listConfig = 'config_list.yaml';

    /** @var array  */
    public $requiredPermissions = ['dubk0ff.breadcrumbmanager.access_templates'];

    /**
     * Templates constructor.
     */
    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('October.System', 'system', 'settings');
        SettingsManager::setContext('Dubk0ff.BreadcrumbManager', 'breadcrumb_manager_templates');
    }

    public function index()
    {
        $this->addJs('/plugins/dubk0ff/breadcrumbmanager/assets/js/bulk-actions.js');
        $this->asExtension('ListController')->index();
    }

    /**
     * @param $query
     */
    public function formExtendQuery($query)
    {
        $query->withTrashed();
    }

    /**
     * @param $query
     */
    public function listExtendQuery($query)
    {
        $query->withTrashed();
    }

    /**
     * @param $recordId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function onDeleteRecord($recordId)
    {
        TemplateModel::withTrashed()->whereId($recordId)->delete();
        Flash::success(Lang::get('dubk0ff.breadcrumbmanager::lang.controllers.messages.delete_success'));

        return Redirect::refresh();
    }

    /**
     * @param $recordId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function onRestoreRecord($recordId)
    {
        TemplateModel::withTrashed()->whereId($recordId)->restore();
        Flash::success(Lang::get('dubk0ff.breadcrumbmanager::lang.controllers.messages.restore_success'));

        return Redirect::refresh();
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function index_onBulkAction()
    {
        if (
            ($bulkAction = post('action')) &&
            ($checkedIds = post('checked')) &&
            is_array($checkedIds) &&
            count($checkedIds)
        ) {
            foreach ($checkedIds as $id) {
                if (! $template = TemplateModel::withTrashed()->whereId($id)->first()) {
                    continue;
                }

                switch ($bulkAction) {
                    case 'soft-delete':
                        $template->delete();
                        break;

                    case 'restore':
                        $template->withTrashed()->restore();
                        break;

                    case 'delete':
                        $template->forceDelete();
                        break;
                }
            }
            Flash::success(Lang::get('dubk0ff.breadcrumbmanager::lang.controllers.messages.bulk_action_success'));
        } else {
            Flash::error(Lang::get('dubk0ff.breadcrumbmanager::lang.controllers.messages.bulk_action_error'));
        }

        return $this->listRefresh();
    }
}