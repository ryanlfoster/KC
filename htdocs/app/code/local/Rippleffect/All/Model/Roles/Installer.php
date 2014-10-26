<?php

class Rippleffect_All_Model_Roles_Installer {
    
    public function installRoles(array $roles) {
        Mage::log("installing new roles");

        $systemRoles = Mage::getModel('admin/roles')->getResourcesList();
//print_r($systemRoles);
        $count = Mage::getModel('admin/role')->getCollection()->count();
        foreach ($roles as $role => $acls) {
            $role_name = ucwords($role);
            $role = Mage::getModel('admin/role')->load($role_name, "role_name");
            if (!$role->hasData()) {
                $role->setData(
                    array(
                        "parent_id" => 0,
                        "tree_level" => 1,
                        "sort_order" => $count,
                        "role_type" => "G",
                        "user_id" => 0,
                        "role_name" => $role_name
                    )
                );
                $role->save();
            }
            
            foreach ($acls as $acl => $data) {
                //print_r($acl. "= " . var_export($data, true) . "\n"); continue;
                $rule = Mage::getModel('admin/rules')->load($acl, "resource_id");
                if (!$rule->hasData()) {
                    $rule->setData(
                        array(
                            "role_id" => $role->getId(),
                            "resource_id" => $acl,
                            "assert_id" => 0,
                            "role_type" => "G",
                        )
                    );
                    if ($data) {
                        $rule->setPermission('allow');
                    }
                    else {
                        $rule->setPermission('deny');
                    }
                }
                else {
                    if ($data && $rule->getPermission() == "deny") {
                        $rule->setPermission("allow");
                    }
                    else if (!$data && $rule->getPermission() == "allow") {
                        $rule->setPermission("deny");
                    }
                }
                print_r($rule);
                $rule->save();
            }
            
            $count++;
        }

    }
    
}
