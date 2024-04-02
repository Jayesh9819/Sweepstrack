<?php
// sjd
ob_start();

$uri = parse_url($_SERVER['REQUEST_URI'])['path'];
include './Router/initialize.php';


if ($uri == $firstparam || $uri == $secondparam) {
    echo '<script type="text/JavaScript"> 
    window.location.replace("./index.php/Portal");
    </script>';
    die();
} else {
    $prefix = $thirdparam;
    $root = $fourthparam;
    $routes = [


        $prefix . $root . '/Login_to_CustCount'                    => './Public/Pages/Signing/login/login.php',
        $prefix . $root . '/Register_to_CustCount'                 => './Public/Pages/Signing/register/register.php',
        $prefix . $root . '/Forgot_pass'                           => './Public/Pages/Signing/forgot/forgot.php',
        //? AGENT DASHBOARD
        $prefix . $root . '/Portal'                                       => './Public/Pages/Portal/main_dashboard.php',
        $prefix . $root . '/Portal_Add_Deposit'                           => './Public/Pages/Portal/add_deposit.php',
        $prefix . $root . '/add_user'                             => './Public/Pages/Portal/add_users.php',
        $prefix . $root . '/Portal_Add_Withdrawal'                         => './Public/Pages/Portal/add_withdrawal.php',
        $prefix . $root . '/Portal_See_Users'                             => './Public/Pages/Portal/see_users.php',
        $prefix . $root . '/Portal_See_Deposits'                          => './Public/Pages/Portal/see_deposits.php',

        $prefix . $root . '/Portal_User_Management'                          => './Public/Pages/Portal/manage_user.php',
        $prefix . $root . '/Portal_Branch_Management'                          => './Public/Pages/Portal/manage_branch.php',

        //forms 
        $prefix . $root . '/cash_up_add'                         => './Public/Pages/Portal/temp.php',
        $prefix . $root . '/cash_out'                         => './Public/Pages/Portal/temp.php',
        $prefix . $root . '/deposit'                         => './Public/Pages/Portal/temp.php',
        $prefix . $root . '/cashup_detail'                         => './Public/Pages/Portal/temp.php',

        $prefix . $root . '/withdrawl'                         => './Public/Pages/Portal/temp.php',
        $prefix . $root . '/platform'                         => './Public/Pages/Portal/temp.php',
        $prefix . $root . '/record'                         => './Public/Pages/Portal/transactionrecord.php',
        $prefix . $root . '/Add_Branch'                         => './Public/Pages/Portal/temp.php',
        $prefix . $root . '/Add_Page'                         => './Public/Pages/Portal/temp.php',
        $prefix . $root . '/Recharge_cashapp'                         => './Public/Pages/Portal/temp.php',
        $prefix . $root . '/Recharge_Platform'                         => './Public/Pages/Portal/temp.php',
        $prefix . $root . '/Redeem_cashapp'                         => './Public/Pages/Portal/temp.php',
        $prefix . $root . '/Redeem_platform'                         => './Public/Pages/Portal/temp.php',
        $prefix . $root . '/Free_Play'                         => './Public/Pages/Portal/temp.php',
        $prefix . $root . '/PlatformUser'                         => './Public/Pages/Portal/platformuser.php',

        $prefix . $root . '/User_Report'                         => './Public/Pages/Portal/user_report.php',
        $prefix . $root . '/Chat_l'                         => './Public/Pages/Chat/index.php',
        $prefix . $root . '/Chat_Screen'                         => './Public/Pages/Chat/home.php',
        //Chat
        $prefix . $root . '/Bulk_Chat'                         => './Public/Pages/Chat/bchats.php',
        $prefix . $root . '/Page_Message'                         => './Public/Pages/Chat/pagelist.php',

        $prefix . $root . '/uploads'                         => './Public/Pages/Chat/uploads',
        //Scripts Route
        $prefix . $root . '/Refer_And_Earn'                         => './Public/Pages/Portal/referandearn.php',
        $prefix . $root . '/Set_Refer'                         => './Public/Pages/Portal/set_referal.php',
        $prefix . $root . '/See_Refer'                         => './Public/Pages/Portal/see_refer.php',
        $prefix . $root . '/Withdraw_Earning'                         => './Public/Pages/Portal/withdrawlearning.php',

        $prefix . $root . '/Scripts'                         => './Public/Pages/Portal/scripts.php',
        $prefix . $root . '/Add_User'                         => './Public/Pages/Portal/temp.php',
        $prefix . $root . '/Add_CashApp'                         => './Public/Pages/Portal/temp.php',
        $prefix . $root . '/Add_Refer'                         => './Public/Pages/Portal/add_refer.php',


        //Edit Fields
        $prefix . $root . '/Edit_User'                         => './Public/Pages/Portal/temp.php',
        $prefix . $root . '/Edit_Cashapp'                         => './Public/Pages/Portal/temp.php',
        $prefix . $root . '/Edit_Branch'                         => './Public/Pages/Portal/temp.php',


        $prefix . $root . '/See_Reports'                          => './Public/Pages/Portal/temp.php',
        $prefix . $root . '/Reports'                          => './Public/Pages/Portal/see_reports.php',
        $prefix . $root . '/PlatformRec'                          => './Public/Pages/Portal/pcrecord.php',

        $prefix . $root . '/update_user'                          => './Public/Pages/Portal/update_user.php',
        $prefix . $root . '/update_agent'                         => './Public/Pages/Portal/update_agent.php',
        $prefix . $root . '/update_manager'                       => './Public/Pages/Portal/update_manager.php',
        $prefix . $root . '/update_supervisor'                    => './Public/Pages/Portal/update_supervisor.php',
        $prefix . $root . '/update_page'                          => './Public/Pages/Portal/update_page.php',
        $prefix . $root . '/update_platform'                          => './Public/Pages/Portal/update_platform.php',
        $prefix . $root . '/update_branch'                          => './Public/Pages/Portal/update_branch.php',

        $prefix . $root . '/update_cashApp'                        => './Public/Pages/Portal/update_cashapp.php',
        $prefix . $root . '/Portal_Agent_Management'              => './Public/Pages/Portal/manage_agent.php',
        $prefix . $root . '/Portal_Supervisor_Management'         => './Public/Pages/Portal/manage_supervisor.php',
        $prefix . $root . '/Portal_Manager_Management'            => './Public/Pages/Portal/manage_manager.php',
        $prefix . $root . '/Portal_Page_Management'               => './Public/Pages/Portal/manage_page.php',
        $prefix . $root . '/Portal_Cashup_Management'             => './Public/Pages/Portal/manage_cashup.php',
        $prefix . $root . '/Portal_Platform_Management'             => './Public/Pages/Portal/manage_platform.php',
        $prefix . $root . '/Portal_Notes'                         => './Public/Pages/Portal/notes_page.php',
        $prefix . $root . '/Portal_Settings'                      => './Public/Pages/Portal/portal_settings.php',
        $prefix . $root . '/See_All_Reports'                      => './Public/Pages/Portal/see_all_record.php',
        $prefix . $root . '/Portal_Chats'                         => './Public/Pages/Chat/index.php',
        $prefix . $root . '/Portal_exchat'                        => './Public/Pages/Portal/portal_exchat.php',


    ];

    function routeToController($uri, $routes)
    {
        if (array_key_exists($uri, $routes)) {
            require $routes[$uri];
        } else {
            abort();
        }
    }

    function abort()
    {

        require  "./Public/Pages/Error/404.php";
        die();
    }
    routeToController($uri, $routes);
}
