<?php
  /*
   * Global helper functions for FORMS
   */

  // prefill form field if it exists
  // does not return a value. don't need to echo this
  function pf($data, $field)
  {
    $value = '';
    if(isset($data[$field])) $value = $data[$field];
    return $value;
  }

  // prefill form field if it exists
  // does not return a value. don't need to echo this
  function pfe($data, $field)
  {
    echo pf($data, $field);
  }

  // returns true or false whether valid email address
  function validEmail($email)
  {
    if(!filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
      return true;
    } else {
      return false;
    }
  }

  /******************************
   * Form field helpers
   *****************************/

  // return a hidden field
  function fid($data, $model, $field, $default = '') {
    $fieldName = 'data[' . $model . '][' . $field . ']';
    $value = $default;
    if(isset($data[$model][$field])) {
      $value = $data[$model][$field];
    }
    $content = '<input type="hidden" name="' . $fieldName . '" value="' . $value . '" />';
    if(isset($data[$model]['_ValidationErrors'][$field])) {
      $content .= '<p class="form-field-error-message">' . $data[$model]['_ValidationErrors'][$field] . '</p>';
    }
    return $content;
  }

  // return a text field and label
  function ftext($data, $title, $model, $field, $required = false, $type = 'text', $class = '') {
    $fieldName = 'data[' . $model . '][' . $field . ']';
    $content = '';
    $content .= '<div class="form-field-group">';
    $content .= '<label for="' . $field . '">' . $title . ($required ? ' <span class="required">*</span>' : '') . '</label>';
    $content .= '<input id="' . $field . '" type="' . $type . '" name="' . $fieldName . '" value="' . (isset($data[$model][$field]) ? $data[$model][$field] : '') . '" placeholder="' . $title . '" ' . ($required ? 'required="required" ' : '') . ($class != '' ? 'class="' . $class . '" ' : '') . '/>';
    if(isset($data[$model]['_ValidationErrors'][$field])) {
      $content .= '<p class="form-field-error-message">' . $data[$model]['_ValidationErrors'][$field] . '</p>';
    }
    $content .= '</div>';
    return $content;
  }

  // return a text field and label
  function fpassword($data, $title, $model, $field, $required = false, $type = 'password') {
    $fieldName = 'data[' . $model . '][' . $field . ']';
    $content = '';
    $content .= '<div class="form-field-group">';
    $content .= '<label for="' . $field . '">' . $title . ($required ? ' <span class="required">*</span>' : '') . '</label>';
    $content .= '<input id="' . $field . '" type="' . $type . '" name="' . $fieldName . '" value="' . (isset($data[$model][$field]) ? $data[$model][$field] : '') . '" placeholder="' . $title . '" ' . ($required ? 'required="required" ' : '') . '/>';
    if(isset($data[$model]['_ValidationErrors'][$field])) {
      $content .= '<p class="form-field-error-message">' . $data[$model]['_ValidationErrors'][$field] . '</p>';
    }
    $content .= '</div>';
    return $content;
  }

  // return a textarea and label
  function ftextarea($data, $title, $model, $field, $required = false) {
    $fieldName = 'data[' . $model . '][' . $field . ']';
    $content = '';
    $content .= '<div class="form-field-group">';
    $content .= '<label for="' . $field . '">' . $title . ($required ? ' <span class="required">*</span>' : '') . '</label>';
    $content .= '<textarea rows="3" id="' . $field . '" name="' . $fieldName . '" placeholder="' . $title . '" ' . ($required ? 'required="required" ' : '') . '>';
    $content .= (isset($data[$model][$field]) ? $data[$model][$field] : '');
    $content .= '</textarea>';
    if(isset($data[$model]['_ValidationErrors'][$field])) {
      $content .= '<p class="form-field-error-message">' . $data[$model]['_ValidationErrors'][$field] . '</p>';
    }
    $content .= '</div>';
    return $content;
  }

  // return a select field and label
  function fselect($data, $title, $model, $field, $options, $required = false, $class = '') {
    $defaultSelected = '';
    $placeholderText = 'Select ' . $title; // which one??
    $placeholderText = 'Please select';
    $content = '';
    $content .= '<div class="form-field-group">';
    if(isset($data[$model][$field])) {
      if($data[$model][$field] == '') {
        $defaultSelected = ' selected="selected"';
      }
    } else {
      $defaultSelected = ' selected="selected"';
    }
    $fieldName = 'data[' . $model . '][' . $field . ']';
    $content .= '<label for="' . $field . '">' . $title . ($required ? ' <span class="required">*</span>' : '') . '</label>';
    $content .= '<select ' . ($required ? 'required="required" ' : '') . 'name="' . $fieldName . '"' . $defaultSelected . ' id="' . $field . '" class="' . $class . '">';
    $content .= '<option value="">' . $placeholderText . '</option>';
    foreach($options as $k => $v) {
      $content .= sprintf('<option %s value="%s">%s</option>',
          (isset($data[$model][$field]) && $data[$model][$field] == $k ? ' selected="selected"' : ''),
          $k, $v
      );
    }
    $content .= '</select>';
    if(isset($data[$model]['_ValidationErrors'][$field])) {
      $content .= '<p class="form-field-error-message">' . $data[$model]['_ValidationErrors'][$field] . '</p>';
    }
    $content .= '</div>';
    return $content;
  }

  // return a checkbox list and label
  function fcheckbox($data, $model, $options) {
    $content = '<ul class="checkbox-list">';
    foreach($options as $k => $v) {
      $fieldName = 'data[' . $model . '][' . $k . ']';
      $content .= sprintf('<li><input %s class="%s" id="%s" type="checkbox" name="%s" value="1"> <label for="%s">%s</label></li>',
          (isset($data[$model][$k]) && $data[$model][$k] == 1 ? ' checked="checked"' : ''),
          (isset($data[$model]['_ValidationErrors'][$k]) ? 'form-field-errors' : ''),
          $k, $fieldName, $k, $v
      );
      if(isset($data[$model]['_ValidationErrors'][$k])) {
        $content .= '<span class="form-field-error-message checkbox-error">' . $data[$model]['_ValidationErrors'][$k] . '</span>';
      }
    }
    $content .= '</ul>';

    return $content;
  }