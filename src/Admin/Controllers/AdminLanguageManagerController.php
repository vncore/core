<?php
namespace Vncore\Core\Admin\Controllers;

use Vncore\Core\Admin\Controllers\RootAdminController;
use Vncore\Core\Admin\Models\Languages;
use Vncore\Core\Admin\Models\AdminLanguage;
use Validator;

class AdminLanguageManagerController extends RootAdminController
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
        $lang = request('lang');
        $position = request('position');
        $keyword = request('keyword');
        $languages = AdminLanguage::getListAll();
        $positionLang = Languages::getPosition();
        $languagesPosition = Languages::getLanguagesPosition($lang, $position, $keyword);
        
        $codeLanguages = AdminLanguage::getCodeAll();
        if (!in_array($lang, array_keys($codeLanguages))) {
            $languagesPositionEL =   [];
        } else {
            $languagesPositionEL = Languages::getLanguagesPosition('en', $position, $keyword);
        }
        $arrayKeyLanguagesPosition = array_keys($languagesPosition);
        $arrayKeyLanguagesPositionEL = array_keys($languagesPositionEL);
        $arrayKeyDiff = array_diff($arrayKeyLanguagesPositionEL, $arrayKeyLanguagesPosition);
        $urlUpdateData = vncore_route_admin('admin_language_manager.update');
        $data = [
            'languages' => $languages,
            'lang' => $lang,
            'positionLang' => $positionLang,
            'position' => $position,
            'keyword' => $keyword,
            'languagesPosition' => $languagesPosition,
            'languagesPositionEL' => $languagesPositionEL,
            'arrayKeyDiff' => $arrayKeyDiff,
            'urlUpdateData' => $urlUpdateData,
            'title' => vncore_language_render('admin.language_manager.title'),
            'subTitle' => '',
            'removeList' => 0, // 1 - Enable function delete list item
            'buttonRefresh' => 0, // 1 - Enable button refresh
            'layout' => 'index',
        ];


        return view($this->vncore_templatePathAdmin.'screen.language_manager')
            ->with($data);
    }

    /**
     * Update data
     *
     * @return void
     */
    public function postUpdate()
    {
        if (!request()->ajax()) {
            return response()->json(['error' => 1, 'msg' => vncore_language_render('admin.method_not_allow')]);
        } else {
            $data = request()->all();
            $lang = vncore_clean($data['lang']);
            $name = vncore_clean($data['name']);
            $value = vncore_clean($data['value']);
            $position = vncore_clean($data['pk']);
            $languages = AdminLanguage::getCodeAll();
            if (!in_array($lang, array_keys($languages))) {
                return response()->json(['error' => 1, 'msg' => vncore_language_render('admin.method_not_allow')]);
            }
            if ($position) {
                Languages::updateOrCreate(
                    ['location' => $lang, 'code' => $name],
                    ['text' => $value, 'position' => $position],
                );
            } else {
                Languages::updateOrCreate(
                    ['location' => $lang, 'code' => $name],
                    ['text' => $value],
                );
            }

            return response()->json(['error' => 0, 'msg' => vncore_language_render('action.update_success')]);
        }
    }

    /**
     * Screen add new record language
     *
     * @return void
     */
    public function add()
    {
        $languages = AdminLanguage::getListAll();
        $positionLang = Languages::getPosition();
        $data = [
            'title' => vncore_language_render('admin.language_manager.add'),
            'positionLang' => $positionLang,
            'languages' => $languages,
        ];
        return view($this->vncore_templatePathAdmin.'screen.language_manager_add')
            ->with($data);
    }

    /**
     * Add new record for language
     *
     * @return void
     */
    public function postAdd()
    {
        $data = request()->all();
        $validator = Validator::make(
            $data,
            [
                'text'         => 'required',
                'position' => 'required_without:position_new',
                'code'         => 'required|unique:"'.Languages::class.'",code|string|max:100',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($data);
        }

        $dataCreate = [
            'code' => trim($data['code']),
            'text' => trim($data['text']),
            'position' => trim(empty($data['position_new']) ? $data['position'] : $data['position_new']),
            'location' => 'en',
        ];
        $dataCreate = vncore_clean($dataCreate, ['text'], true);
        Languages::insert($dataCreate);

        return redirect(vncore_route_admin('admin_language_manager.index'))->with('success', vncore_language_render('action.create_success'));
    }
}
