<?php
if (! function_exists('check_access')) {
    function check_access($nama)
    { 
        $data = \App\Models\RoleMenu::checkAccess($nama);        
        return $data;
    }
}
if (! function_exists('must_access')) {
    function must_access($nama)
    { 
        $data = \App\Models\RoleMenu::checkAccess($nama);        
        if ($data) {
            return true;
        } else {
            abort(404);
        }
    }
}