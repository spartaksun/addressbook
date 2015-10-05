<?php

namespace spartaksun\addresses\components;

/**
 * Class EmployeeTree
 * Builds HTML from list of employee
 *
 * @package spartaksun\addresses\components
 */
class EmployeeTree
{
    /**
     * @var array of items
     */
    private $_items;


    public function __construct()
    {
        $items = Db::getInstance()->selectAll(Db::TABLE_EMPLOYEE);
        $this->_items = $items;
    }

    /**
     * HTML list tree
     * @param bool $links
     * @return string
     */
    public function toHtml($links = false)
    {
        return $this->htmlFromArray($this->itemArray(), $links);
    }

    /**
     * @return array
     */
    private function itemArray()
    {
        $result = array();
        foreach ($this->_items as $item) {
            if (empty($item['supervisor_id'])) {
                $result[] = array(
                    'id' => $item['id'],
                    'name' => $item['name'],
                    'title' => $item['title'],
                    'email' => $item['email'],
                    'children' => $this->itemWithChildren($item)
                );
            }
        }
        return $result;
    }

    /**
     * @param $item
     * @return array
     */
    private function childrenOf($item)
    {
        $result = array();
        foreach ($this->_items as $i) {
            if ((int)$i['supervisor_id'] == (int)$item['id']) {
                $result[] = $i;
            }
        }

        return $result;
    }

    /**
     * @param $item
     * @return array
     */
    private function itemWithChildren($item)
    {
        $result = array();
        $children = $this->childrenOf($item);
        foreach ($children as $child) {
            if($child['id'] == $item['id']) {
                continue;
            }
            $result[] = array(
                'id' => $child['id'],
                'name' => $child['name'],
                'title' => $child['title'],
                'email' => $child['email'],
                'children' => $this->itemWithChildren($child)
            );
        }
        return $result;
    }

    /**
     * @param $array
     * @param null $editLinks
     * @return string
     */
    private function htmlFromArray($array, $editLinks = null)
    {
        $html = '';

        foreach ($array as $id => $value) {
            $html .= "<ul><li>";
            $html .= $value['title'] . ", " . $value['name'] . ", " . $value['email'];
            $hasChildren = count($value['children']) > 0;

            if ($editLinks) {
                $html .= " <a href='"
                    . Html::createUrl('/admin/create', array('id' => $value['id']))
                    . "'>Add</a>";
                if (!$hasChildren) {
                    $url = Html::createUrl('/admin/delete', array('id' => $value['id']));
                    $html .= " <a class='delete' href='" . $url . "' onclick='return false;' >Delete</a>";
                } else {
                    $html .= " <a class='nondelete' href='' onclick='return false;' >Delete</a>";
                }
            }
            $html .= "</li>";

            if ($hasChildren) {
                $html .= $this->htmlFromArray($value['children'], $editLinks);
            }
            $html .= "</ul>";
        }
        return $html;
    }
}
