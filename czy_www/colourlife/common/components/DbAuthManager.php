<?php

class DbAuthManager extends CDbAuthManager
{
    public function getAuthAssignmentUsers($itemName)
    {
        $rows = $this->db->createCommand()->select()
            ->from($this->assignmentTable)
            ->where('itemname=:itemname', array(
                ':itemname' => $itemName,
            ))->queryAll();
        $ids = array();
        foreach ($rows as $row) {
            $ids[] = $row['userid'];
        }
        return $ids;
    }
}