<?php
/***********************************************************************************************************************
 * ╔═══╗ ╔══╗ ╔═══╗ ╔════╗ ╔═══╗ ╔══╗  ╔╗╔╗╔╗ ╔═══╗ ╔══╗   ╔══╗  ╔═══╗ ╔╗╔╗ ╔═══╗ ╔╗   ╔══╗ ╔═══╗ ╔╗  ╔╗ ╔═══╗ ╔╗ ╔╗ ╔════╗
 * ║╔══╝ ║╔╗║ ║╔═╗║ ╚═╗╔═╝ ║╔══╝ ║╔═╝  ║║║║║║ ║╔══╝ ║╔╗║   ║╔╗╚╗ ║╔══╝ ║║║║ ║╔══╝ ║║   ║╔╗║ ║╔═╗║ ║║  ║║ ║╔══╝ ║╚═╝║ ╚═╗╔═╝
 * ║║╔═╗ ║╚╝║ ║╚═╝║   ║║   ║╚══╗ ║╚═╗  ║║║║║║ ║╚══╗ ║╚╝╚╗  ║║╚╗║ ║╚══╗ ║║║║ ║╚══╗ ║║   ║║║║ ║╚═╝║ ║╚╗╔╝║ ║╚══╗ ║╔╗ ║   ║║
 * ║║╚╗║ ║╔╗║ ║╔╗╔╝   ║║   ║╔══╝ ╚═╗║  ║║║║║║ ║╔══╝ ║╔═╗║  ║║─║║ ║╔══╝ ║╚╝║ ║╔══╝ ║║   ║║║║ ║╔══╝ ║╔╗╔╗║ ║╔══╝ ║║╚╗║   ║║
 * ║╚═╝║ ║║║║ ║║║║    ║║   ║╚══╗ ╔═╝║  ║╚╝╚╝║ ║╚══╗ ║╚═╝║  ║╚═╝║ ║╚══╗ ╚╗╔╝ ║╚══╗ ║╚═╗ ║╚╝║ ║║    ║║╚╝║║ ║╚══╗ ║║ ║║   ║║
 * ╚═══╝ ╚╝╚╝ ╚╝╚╝    ╚╝   ╚═══╝ ╚══╝  ╚═╝╚═╝ ╚═══╝ ╚═══╝  ╚═══╝ ╚═══╝  ╚╝  ╚═══╝ ╚══╝ ╚══╝ ╚╝    ╚╝  ╚╝ ╚═══╝ ╚╝ ╚╝   ╚╝
 *----------------------------------------------------------------------------------------------------------------------
 * @author Gartes | sad.net79@gmail.com | Skype : agroparknew | Telegram : @gartes
 * @date 05.10.2020 20:55
 * @copyright  Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 **********************************************************************************************************************/
defined('_JEXEC') or die; // No direct access to this file


?>
<style>
    div#formAdd {
        /* min-height: 240px; */
        width: 560px;
        overflow: visible;
        padding-bottom: 10px;
    }

    div#formAdd .control-group {
        float: left;
    }

    div#formAdd .control-group > div {
        float: left;
    }

    div#formAdd .control-group > div.control-label {
        min-width: 200px;
    }
    div#form-wrp {
    }

    div#formAdd > div {
        float: left;
    }

    div#formAdd div#form-add-footer {
        width: 100%;
    }

    div#formAdd div#form-add-footer #toolbar-apply {
        float: right;
    }
    div#formAdd #success {
        float: left;
        width: 100%;
        text-align: center;
        margin-bottom: 40px;
        font-size: 21px;
        font-weight: 600;
    }
</style>
<div id="formAdd">
    <div id="form-wrp">
        <form id="addFelterParams">
            <input style="display: none" id="hiddenElement" type="text" />
            <?php

            foreach ($this->filtersData as $var_name => $filtersDatum)
            {
                if (strpos($var_name, 'custom_f_') !== false)
                {
                    ?>
                    <div class="control-group">
                        <div class="control-label">
                            <label id="jform_params_<?= $var_name ?>-lbl" for="jform_<?= $var_name ?>"
                                   class="params-<?= $var_name ?>">
                                <?= $filtersDatum['header'] ?>
                            </label>
                        </div>

                        <div class="controls">
                            <select id="jform_<?= $filtersDatum['var_name'] ?>"
                                    tabindex="31"
                                    name="jform[felter_params][<?= $var_name ?>][]" multiple="multiple" style="">
                                <option value=""></option>
                                <?php

                                foreach ($filtersDatum['options'] as $i => $Arr)
                                {
                                    $selected = null ;
                                    if (isset($this->filterContentParam->{$var_name}))
                                    {
                                        $arrFLT = $this->filterContentParam->{$var_name} ;
                                        if ( in_array( $Arr['id'] , $arrFLT) )
                                        {
                                            $selected = 'selected="selected"';
                                        }#END IF
                                    }#END IF
                                    ?>
                                    <option <?= $selected ?> value="<?= $Arr['id'] ?>" >
                                        <?= $Arr['label'] ?>
                                    </option>
                                    <?php
                                }
                                ?>

                            </select>
                        </div>
                    </div>

                    <?php

                    /*echo '<pre>';
                    print_r($filtersDatum);
                    echo '</pre>' . __FILE__ . ' ' . __LINE__;*/
                }

            }#END FOREACH


            ?>
            <input type="hidden" name="id" value="<?= $this->app->input->get('id' , false , 'INT') ?>">
            <input type="hidden" name="group" value="editors-xtd">
            <input type="hidden" name="plugin" value="customfilters_content">
            <input type="hidden" name="option" value="com_ajax">
            <input type="hidden" name="task" value="saveForm">



        </form>
    </div>
    <div class="btn-toolbar" role="toolbar" aria-label="Панель инструментов" id="form-add-footer">
        <div class="btn-wrapper" id="toolbar-apply">
            <button class="btn btn-small button-apply btn-success">
                <span class="icon-apply icon-white" aria-hidden="true"></span>
                Сохранить
            </button>
        </div>
    </div>

</div>





















