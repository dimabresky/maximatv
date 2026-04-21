<?php

namespace Maxima\Helpers;

class FavoritesHelper
{
    private $hlDataClass;

    public function __construct()
    {
        $this->hlDataClass = HighloadHelper::getFavoritesHlDataClass();
    }

    public function addFavoriteItem($userId, $elementId)
    {
        if (!$this->isItemExist($userId, $elementId)) {
            $this->hlDataClass::add(['UF_USER_ID' => $userId, 'UF_ELEMENT_ID' => $elementId]);
        }
    }

    public function deleteFavoriteItem($userId, $elementId)
    {
        $item = $this->hlDataClass::getList([
                'select' => ['ID'],
                'filter' => ['UF_USER_ID' => $userId, 'UF_ELEMENT_ID' => $elementId]]
        )->fetch();
        $this->hlDataClass::delete((int)$item['ID']);
    }

    public function getUserFavoriteItems($userId)
    {
        $result = [];

        $dbRes = $this->hlDataClass::getList([
                'select' => ['UF_ELEMENT_ID'],
                'filter' => ['UF_USER_ID' => $userId],
        ]);
        while ($item = $dbRes->fetch()) {
            $result[] = $item['UF_ELEMENT_ID'];
        }

        return $result;
    }

    public function clearUserFavoriteItems($userId)
    {
        $ids = [];

        $dbRes = $this->hlDataClass::getList([
                'select' => ['ID'],
                'filter' => ['UF_USER_ID' => $userId]]
        );
        while ($item = $dbRes->fetch()) {
            $ids[] = $item['UF_ELEMENT_ID'];
        }

        if (count($ids) > 0) {
            foreach ($ids as $id) {
                $this->hlDataClass::delete($id);
            }
        }
    }

    public function isItemExist($userId, $elementId)
    {
        $dbRes = $this->hlDataClass::getList([
                'select' => ['ID'],
                'filter' => ['UF_USER_ID' => $userId, 'UF_ELEMENT_ID' => $elementId]]
        );

        return $dbRes->getSelectedRowsCount() > 0;
    }
}