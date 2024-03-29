<?php
/**
 * Description
 * passwordLogin.php
 */

namespace account;

use APS\ASAPI;
use APS\ASResult;
use APS\User;

class passwordLogin extends ASAPI{

    const scope = ASAPI_Scope_Public;
    const mode = ASAPI_Mode_Json;

    public function run(): ASResult
    {
        $getUserid = User::common()->searchUserByInfo( $this->params['account'] );
        if( !$getUserid->isSucceed() ){ return $getUserid;  }

        $userid = $getUserid->getContent();

        $checkPassword = User::common()->checkPassword( $this->params['password'], $userid );
        if( !$checkPassword->isSucceed() ){ return $checkPassword; }

        return User::common()->systemAuthorize($userid, 'manager');
    }

}