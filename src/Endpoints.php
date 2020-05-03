<?php
/**
 * @author xp
 */
namespace FeishuBot;

class Endpoints
{
    const GET_APP_ACCESS_TOKEN = '/open-apis/auth/v3/app_access_token/internal/';
    const GET_TENANT_ACCESS_TOKEN = '/open-apis/auth/v3/tenant_access_token/internal/';

    const ASK_FOR_APP_TICKET = '/open-apis/auth/v3/app_ticket/resend/';

    const OAUTH_REQUEST = '/open-apis/authen/v1/index';
    const OAUTH_GET_ACCESS_TOKEN = '/open-apis/authen/v1/access_token';
    const OAUTH_REFRESH_ACCESS_TOKEN = '/open-apis/authen/v1/refresh_access_token';
    const OAUTH_GET_USER_INFO = '/open-apis/authen/v1/user_info';

    const IS_USER_ADMIN = '/open-apis/application/v3/is_user_admin';
    const GET_ADMIN_SCOPE = '/open-apis/contact/v1/user/admin_scope/get';
    const GET_APP_VISIBILITY = '/open-apis/application/v1/app/visibility';

    const SEND_MESSAGE = '/open-apis/message/v4/send/';
    const UPLOAD_IMAGE = '/open-apis/image/v4/put/';
    const GET_IMAGE = '/open-apis/image/v4/get';
    const GET_FILE = '/open-apis/open-file/v1/get';
}
