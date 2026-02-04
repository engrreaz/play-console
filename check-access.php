<?php


// get package_id, package_name, tier, valid_module, active_module from scinfo table where sccode='{$sccode}'
$package_id = '';
$package_name = '';
$tier = '';
$valid_module = '';
$active_module = '';

$sql_scinfo = "SELECT package_id, package_name, tier, valid_module, active_module FROM scinfo WHERE sccode='{$sccode}'";
$result_scinfo = $conn->query($sql_scinfo);

if ($result_scinfo->num_rows > 0) {
    // output data of each row
    while ($row_scinfo = $result_scinfo->fetch_assoc()) {
        $package_id = $row_scinfo["package_id"];
        $package_name = $row_scinfo["package_name"];
        $tier = $row_scinfo["tier"] ?? 'A';
        $valid_module = $row_scinfo["valid_module"];
        $active_module = $row_scinfo["active_module"];
    }
}






$valid_modules = explode(' | ', $valid_module);
$active_modules = explode(' | ', $active_module);





$permission = 0; // Default permission

if ($is_admin > 3 || $is_chief > 0) {
    $permission = 3; // Full access for admin or chief
    // echo 'Admin/Chief Access';
} else {


    $sql_permission = "
    SELECT * FROM permission_map_app 
    WHERE page_name = '$curfile' 
    AND (email = '$usr' OR email IS NULL OR email = '') 
    AND (userlevel = '$reallevel' OR userlevel IS NULL OR userlevel = '') 
    AND (sccode = '$sccode' OR sccode = 0) 
    ORDER BY email DESC, userlevel DESC, sccode DESC 
    LIMIT 1
";

    $result_permission = $conn->query($sql_permission);

    if ($result_permission->num_rows > 0) {
        $row_permission = $result_permission->fetch_assoc();
        $permission = $row_permission['permission'] ?? 0;
        $module_name = $row_permission['module'] ?? '';
        $p_email = $row_permission['email'] ?? '';
        $p_userlevel = $row_permission['userlevel'] ?? '';
        $p_sccode = $row_permission['sccode'] ?? 0;
        $p_root_page = $row_permission['root_page'] ?? '';

        if (in_array($module_name, $active_modules)) {



            // echo '<hr>';
            // echo $permission . ' | ' . $p_email . ' | ' . $p_userlevel . ' | ' . $p_sccode . ' | ' . $p_root_page;

            if ($p_email == '' or $p_userlevel == '' or $p_sccode == 0) {
                // echo 'Active';
                // echo '(' . $permission . ') ';
                $sql = "
                    SELECT *
                    FROM permission_map
                    WHERE page_name = '$p_root_page'
                    AND (
                            email = '$usr'
                        OR userlevel = '$userlevel'
                        OR sccode = '$sccode'
                    )
                    ORDER BY 
                        CASE 
                            WHEN email = '$usr' THEN 1
                            WHEN sccode = '$sccode' THEN 2
                            WHEN userlevel = '$userlevel' THEN 3
                            ELSE 4
                        END
                    LIMIT 1

                ";

                // echo $sql;
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $permission = $row['permission'];
                }
            }




        } else {
            // Module is not active, set permission to 0
            $permission = 0;
        }
        if ($module_name == 'Core') {
            $permission = 3;
        }
        if ($module_name == 'Developement') {
            $permission = 0;
        }
    } else {
        // Module is not active, set permission to 0
        $permission = 0;
    }

    

}





// --- get package info
// --- get package category
// --- get module active 


// --- get current page link $permission
// --- get override permission if any web/console
// --- get app override permission if any

if ($permission == 0) {
    include 'no-access.php';
    exit();
}