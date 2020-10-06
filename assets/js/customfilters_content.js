/***********************************************************************************************************************
 * ╔═══╗ ╔══╗ ╔═══╗ ╔════╗ ╔═══╗ ╔══╗  ╔╗╔╗╔╗ ╔═══╗ ╔══╗   ╔══╗  ╔═══╗ ╔╗╔╗ ╔═══╗ ╔╗   ╔══╗ ╔═══╗ ╔╗  ╔╗ ╔═══╗ ╔╗ ╔╗ ╔════╗
 * ║╔══╝ ║╔╗║ ║╔═╗║ ╚═╗╔═╝ ║╔══╝ ║╔═╝  ║║║║║║ ║╔══╝ ║╔╗║   ║╔╗╚╗ ║╔══╝ ║║║║ ║╔══╝ ║║   ║╔╗║ ║╔═╗║ ║║  ║║ ║╔══╝ ║╚═╝║ ╚═╗╔═╝
 * ║║╔═╗ ║╚╝║ ║╚═╝║   ║║   ║╚══╗ ║╚═╗  ║║║║║║ ║╚══╗ ║╚╝╚╗  ║║╚╗║ ║╚══╗ ║║║║ ║╚══╗ ║║   ║║║║ ║╚═╝║ ║╚╗╔╝║ ║╚══╗ ║╔╗ ║   ║║
 * ║║╚╗║ ║╔╗║ ║╔╗╔╝   ║║   ║╔══╝ ╚═╗║  ║║║║║║ ║╔══╝ ║╔═╗║  ║║─║║ ║╔══╝ ║╚╝║ ║╔══╝ ║║   ║║║║ ║╔══╝ ║╔╗╔╗║ ║╔══╝ ║║╚╗║   ║║
 * ║╚═╝║ ║║║║ ║║║║    ║║   ║╚══╗ ╔═╝║  ║╚╝╚╝║ ║╚══╗ ║╚═╝║  ║╚═╝║ ║╚══╗ ╚╗╔╝ ║╚══╗ ║╚═╗ ║╚╝║ ║║    ║║╚╝║║ ║╚══╗ ║║ ║║   ║║
 * ╚═══╝ ╚╝╚╝ ╚╝╚╝    ╚╝   ╚═══╝ ╚══╝  ╚═╝╚═╝ ╚═══╝ ╚═══╝  ╚═══╝ ╚═══╝  ╚╝  ╚═══╝ ╚══╝ ╚══╝ ╚╝    ╚╝  ╚╝ ╚═══╝ ╚╝ ╚╝   ╚╝
 *----------------------------------------------------------------------------------------------------------------------
 * @author Gartes | sad.net79@gmail.com | Skype : agroparknew | Telegram : @gartes
 * @date 05.10.2020 16:26
 * @copyright  Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 **********************************************************************************************************************/

window.Customfilters_content = function (){
    var $ = jQuery ;
    var self = this ;
    this.params = {} ;
    /**
     * Параметры Ajax по умолчанию
     * @type {{task: null, plugin: string, format: string, group: string, option: string}}
     */
    this.AjaxDefaultData = {
        group:  'undefined' ,
        plugin: 'undefined' ,
        option: 'com_ajax'  ,
        format: 'json'      ,
        task: null          ,
    };
    this.Init = function (){
        this.addEvt();
        this.params = Joomla.getOptions('customfilters_content',{});
        this.AjaxDefaultData = {
            group: this.params.group,
            plugin: this.params.plugin,
            option: 'com_ajax',
            format: 'json',
            task: null,
        };


    };
    this.addEvt = function (){
        $('.addCustomFilter').on('click' , self.onAddCustomFilterClick )
    };
    this.onAddCustomFilterClick = function (event){
        event.preventDefault();
        if ( !self.params.id ) {
            alert('Для добавления статьи к фильтру нужно сохранить статью') ;
            return  ;
        }
        self.loadModalFilterSelect();
    };
    /**
     * Загрузка формы
     */
    this.loadModalFilterSelect = function (){

        var data = $.extend(true, this.AjaxDefaultData, {
            task : 'getForm' ,
            id  :  this.params.id ,
        });

        console.log( this.params )

        this.getModul("Ajax").then(function (Ajax) {
            Ajax.send(data).then(function (response) {
                console.log( response ) ;

                self.__loadModul.Fancybox().then(function (a) {
                    a.open( response.data.html , {
                        baseClass: "modalExport",
                        afterShow   : function(instance, current) {
                            $('#addFelterParams select').chosen().trigger('chosen:close');

                            $('#form-add-footer button').on('click' , self.sendForm  )
                        },
                    })
                });
            });
        });
    }
    this.sendForm = function ( event ){
        event.preventDefault();

       /* var data = $.extend(true, self.AjaxDefaultData, {
            task : 'saveForm' ,
            formData  :  $('#addFelterParams').serialize() ,
        });*/

        console.log( self.AjaxDefaultData )

        var data =  $('#addFelterParams').serialize() ;
        self.getModul("Ajax").then(function (Ajax) {
            Ajax.send(data).then(function (response) {
                $('#formAdd').html( response.data.html )
                console.log( response ) ;
            });
        });
    }



    this.Init();
};


(function (){
    window.Customfilters_content.prototype = new GNZ11();
    new Customfilters_content();
})()
