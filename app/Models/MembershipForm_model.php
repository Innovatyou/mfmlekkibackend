<?php

namespace App\Models;

use App\Models\Basemodel;

class MembershipForm_model extends Basemodel
{
  public $status;
  public $message;

  const CORE_MEMBER_COLUMNS = ['firstname', 'lastname', 'email', 'phonenumber', 'gender', 'dob', 'address'];

  public function __construct()
  {
    parent::__construct();
    $this->status = $this->applocal['error'];
    $this->message = $this->applocal['process_error'];
  }

  private function decorate($row)
  {
    $row->options = $row->options ? json_decode($row->options) : null;
    $row->required = (bool) $row->required;
    $row->is_core = (bool) $row->is_core;
    return $row;
  }

  function fetchAll()
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_membership_form_fields');
    $builder->select('tbl_membership_form_fields.*');
    $builder->orderBy('sort_order', 'ASC');
    $builder->orderBy('id', 'ASC');
    $result = $builder->get()->getResult();
    foreach ($result as $r) $this->decorate($r);
    return $result;
  }

  function fetchActive()
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_membership_form_fields');
    $builder->select('tbl_membership_form_fields.*');
    $builder->where('status', 'active');
    $builder->orderBy('sort_order', 'ASC');
    $builder->orderBy('id', 'ASC');
    $result = $builder->get()->getResult();
    foreach ($result as $r) $this->decorate($r);
    return $result;
  }

  function getInfo($id)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_membership_form_fields');
    $builder->select('tbl_membership_form_fields.*');
    $builder->where('id', $id);
    $row = $builder->get()->getRow(0);
    return $row ? $this->decorate($row) : null;
  }

  function checkKeyExists($key, $id = 0)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_membership_form_fields');
    $builder->select('id');
    $builder->where('field_key', $key);
    if ($id != 0) $builder->where('id !=', $id);
    return $builder->get()->getResult();
  }

  function slugifyKey($label)
  {
    $key = strtolower(trim($label));
    $key = preg_replace('/[^a-z0-9]+/', '_', $key);
    $key = trim($key, '_');
    return $key !== '' ? $key : 'field_' . time();
  }

  function addNew($info)
  {
    $db = \Config\Database::connect("default");
    $now = date('Y-m-d H:i:s');
    $info['is_core'] = 0;
    $info['created_at'] = $now;
    $info['updated_at'] = $now;
    $maxOrder = $db->table('tbl_membership_form_fields')->selectMax('sort_order')->get()->getRow(0);
    $info['sort_order'] = ($maxOrder && $maxOrder->sort_order !== null) ? ((int) $maxOrder->sort_order + 1) : 1;
    $builder = $db->table('tbl_membership_form_fields');
    $builder->insert($info);
    $this->status = $this->applocal['ok'];
    $this->message = "Field added successfully.";
  }

  function edit($info, $id)
  {
    $field = $this->getInfo($id);
    if ($field && $field->is_core) {
      // Core fields keep their field_key and type (tbl_members schema depends on it)
      unset($info['field_key'], $info['field_type'], $info['options']);
    }
    $info['updated_at'] = date('Y-m-d H:i:s');
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_membership_form_fields');
    $builder->where('id', $id);
    $builder->update($info);
    $this->status = $this->applocal['ok'];
    $this->message = "Field updated successfully.";
  }

  function deleteField($id)
  {
    $field = $this->getInfo($id);
    if ($field && $field->is_core) {
      $this->status = $this->applocal['error'];
      $this->message = "Core fields cannot be deleted — you can mark them inactive instead.";
      return;
    }
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_membership_form_fields');
    $builder->where('id', $id);
    $builder->delete();
    $this->status = $this->applocal['ok'];
    $this->message = "Field deleted successfully.";
  }

  function moveUp($id)
  {
    $all = $this->fetchAll();
    $ids = array_map(fn($f) => $f->id, $all);
    $index = array_search((int) $id, $ids, true);
    if ($index === false || $index === 0) return;
    $this->swapOrder($all[$index], $all[$index - 1]);
  }

  function moveDown($id)
  {
    $all = $this->fetchAll();
    $ids = array_map(fn($f) => $f->id, $all);
    $index = array_search((int) $id, $ids, true);
    if ($index === false || $index === count($all) - 1) return;
    $this->swapOrder($all[$index], $all[$index + 1]);
  }

  private function swapOrder($a, $b)
  {
    $db = \Config\Database::connect("default");
    $db->table('tbl_membership_form_fields')->where('id', $a->id)->update(['sort_order' => $b->sort_order]);
    $db->table('tbl_membership_form_fields')->where('id', $b->id)->update(['sort_order' => $a->sort_order]);
  }

  // ─── Answers ────────────────────────────────────────────────────────

  function saveAnswer($memberId, $field, $value)
  {
    if (is_array($value)) $value = implode(', ', $value);
    $db = \Config\Database::connect("default");
    $db->table('tbl_membership_field_answers')->insert([
      'member_id'  => $memberId,
      'field_id'   => $field->id,
      'field_key'  => $field->field_key,
      'label'      => $field->label,
      'value'      => $value,
      'created_at' => date('Y-m-d H:i:s'),
    ]);
  }

  function getAnswersForMember($memberId)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_membership_field_answers');
    $builder->select('tbl_membership_field_answers.*');
    $builder->where('member_id', $memberId);
    $builder->orderBy('id', 'ASC');
    return $builder->get()->getResult();
  }
}
