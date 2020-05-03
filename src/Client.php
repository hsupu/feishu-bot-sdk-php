<?php
/**
 * @author xp
 */
namespace FeishuBot;

use FeishuBot\Exception\FeishuClientRequestException;
use FeishuBot\Exception\FeishuClientResponseException;
use FeishuBot\Message\IClientMessage;
use FeishuBot\Model\ClientMessageReceiver;
use FeishuBot\Model\ClientUser;

class Client extends ClientBase
{
    public function isUserAdmin(ClientUser $user) : bool {
        if (!$user->isSet()) {
            throw new FeishuClientRequestException(FeishuClientRequestException::CODE_BAD_REQUEST, [
                'user' => $user,
            ]);
        }
        list($userFieldName, $userId) = $user->get();
        $query = self::genQuery([
            $userFieldName => $userId,
        ]);
        $result = $this->callGet(Endpoints::IS_USER_ADMIN, $query, $this->getTenantAccessToken());
        return $result->data->is_app_admin;
    }

    public function getAdminScope(ClientUser $user) : object {
        if (!$user->isSet()) {
            throw new FeishuClientRequestException(FeishuClientRequestException::CODE_BAD_REQUEST, [
                'user' => $user,
            ]);
        }
        list($userFieldName, $userId) = $user->get();
        $query = self::genQuery([
            $userFieldName => $userId,
        ]);
        $result = $this->callGet(Endpoints::GET_ADMIN_SCOPE, $query, $this->getTenantAccessToken());
        return $result->data;
    }

    public function getAppVisibility($appId, $pageSize = null, $pageToken = null) : object {
        $query = self::genQuery([
            'app_id' => $appId,
        ], [
            'user_page_size' => $pageSize,
            'user_page_token' => $pageToken,
        ]);
        $result = $this->callGet(Endpoints::GET_APP_VISIBILITY, $query, $this->getTenantAccessToken());
        return $result->data;
    }

    public function sendMessage(ClientMessageReceiver $receiver, IClientMessage $message, ?string $refMessageId = null) : string {
        if (!$receiver->isSet()) {
            throw new FeishuClientRequestException(FeishuClientRequestException::CODE_BAD_REQUEST, [
                'receiver' => $receiver,
            ]);
        }
        list($receiverFieldName, $receiverId) = $receiver->get();
        $request = [
            $receiverFieldName => $receiverId,
            'msg_type' => $message::getMessageType(),
            'content' => $message->render(),
        ];
        if (!is_null($refMessageId)) {
            $request['root_id'] = $refMessageId;
        }
        $result = $this->callPost(Endpoints::SEND_MESSAGE, $request, $this->getTenantAccessToken());
        return $result->data->message_id;
    }

    public function uploadImage(string $filename, string $bytes, string $type = 'message') : string {
        $form = [
            'image' => [$bytes, $filename],
            'image_type' => $type,
        ];
        $result = $this->callPostFormData(Endpoints::UPLOAD_IMAGE, $form, $this->getTenantAccessToken());
        return $result->data->image_key;
    }

    public function getImage(string $key) : string {
        $query = self::genQuery([
            'image_key' => $key,
        ]);
        return $this->callGetStream(Endpoints::GET_IMAGE, $query, $this->getTenantAccessToken());
    }

    public function getFile(string $key) : string {
        $query = self::genQuery([
            'file_key' => $key,
        ]);
        return $this->callGetStream(Endpoints::GET_FILE, $query, $this->getTenantAccessToken());
    }

    public function oauthGetEntry(string $redirectURL, string $state) : string {
        return $this->genURI(Endpoints::OAUTH_REQUEST, self::genQuery([
            'app_id' => $this->config->appId,
            'redirect_url' => $redirectURL,
            'state' => $state,
        ]));
    }

    public function oauthGetAccessToken(string $code) : object {
        $request = [
            'app_access_token' => $this->getAppAccessToken(),
            'grant_type' => 'authorization_code',
            'code' => $code,
        ];
        $result = $this->callPost(Endpoints::OAUTH_GET_ACCESS_TOKEN, $request);
        return $result->data;
    }

    public function oauthRefreshAccessToken(string $refreshToken) : object {
        $request = [
            'app_access_token' => $this->getAppAccessToken(),
            'grant_type' => 'refresh_token',
            'refresh_token' => $refreshToken,
        ];
        $result = $this->callPost(Endpoints::OAUTH_REFRESH_ACCESS_TOKEN, $request);
        return $result->data;
    }

    public function oauthGetUserInfo(string $userAccessToken) : object {
        $result = $this->callGet(Endpoints::OAUTH_GET_USER_INFO, null, $userAccessToken);
        return $result->data;
    }
}
