<?php 


function openForm(string $page, string $action, string $method="POST")
{
  echo '<form action="'.$action.'" method="'.$method.'" >'.PHP_EOL
     .'   <input type="hidden" name="page" value="'.$page.'" />'.PHP_EOL;
}

function closeForm(string $submit_caption="Versturen")
{
  echo '    <br><button type="submit" value="submit">'.$submit_caption.'</button><br><br><br>'.PHP_EOL
    .'  </form>'.PHP_EOL;
}


function showFields(array $arr_postresult = array())
{
  foreach ($arr_postresult['arr_fieldinfo'] as $fieldname => $fieldinfo)
  {
    $current_value = (isset($arr_postresult[$fieldname]) ? $arr_postresult[$fieldname] : ''); 
    echo '    <label for="'.$fieldname.'">'.$fieldinfo['label'].'</label><br />'.PHP_EOL;
    switch ($fieldinfo['type'])
    {
      case "textarea" :
        echo '    <textarea name="'
          .$fieldname
          .'" placeholder="'
          .$fieldinfo['placeholder'].'">'
          .$current_value
          .'</textarea>'
          .PHP_EOL;
        break;
      
      case "select" :
        echo '    <select name="'.$fieldname.'">'.PHP_EOL;
        if (isset($fieldinfo['options_func']))
        { 
          $options = call_user_func($fieldinfo['options_func']);
          if ($options)
          {
            foreach ($options as $key => $value)
            {
              $selected = $current_value==$value ? "selected" : "";
              echo '<option value="'.$value.'" '.$selected.'>'.$key.'</option>'.PHP_EOL;
            }
          }           
        }
        echo '    </select>'.PHP_EOL;
        break;

      case "pass":
      default : 
        echo '    <input type="'.$fieldinfo['type']
          .'" name="'.$fieldname
          .'" placeholder="'.$fieldinfo['placeholder'].'"value="' 
          .$current_value
          .'" />'
          .PHP_EOL;
        break;
    }
    echo '<br />'.PHP_EOL;
    if (isset($arr_postresult[$fieldname.'_err']))
    {
      echo '  <span class="error">* '.$arr_postresult[$fieldname.'_err'].'</span><br/>';
    }     
  }
}

function showForm($result) {

    openForm($result['page'],'');
    showFields($result);
    closeForm();
}

?>