(function (plugins, editPost, element, components, data, compose) {

    const el = element.createElement;

    const {Fragment} = element;
    const {registerPlugin} = plugins;
    const {PluginSidebar, PluginDocumentSettingPanel, PluginSidebarMoreMenuItem} = editPost;
    const {PanelBody,MenuItem, withState, DropdownMenu, arrowUp, arrowDown, ToggleControl, PanelRow, TextControl, TextareaControl, CheckboxControl} = components;
    const {withSelect, withDispatch} = data;


    let MetaTextControl = compose.compose(
        withDispatch(function (dispatch, props) {
            return {
                setMetaValue: function (metaValue) {
                    dispatch('core/editor').editPost(
                        {meta: {[props.metaKey]: metaValue}}
                    );
                }
            }
        }),
        withSelect(function (select, props) {
            return {
                metaValue: select('core/editor').getEditedPostAttribute('meta')[props.metaKey],
                postId: select('core/editor').getCurrentPostId()
            }
        }))(function (props) {
            const generateUniqueId = (min, max) => Math.floor(Math.random() * (max - min)) + min;
            let random = generateUniqueId(1, 9999999)
            function getMeta(url){
                let img = new Image();
                img.onload = function(){
                    let sizeBlock = document.querySelector(`.image-size_${random}`)
                    sizeBlock.innerHTML = `<div>Original image size: ${this.width}x${this.height}</div>`
                };
                img.src = url;
            }
            return el('div', {
                    className: props.class,
                    style: {display: 'none'}
                },
                el(PanelBody, {},
                    el('a',{
                            href:props.metaValue,
                            target:"_blank",
                            className: 'image-new-blank',
                        },
                        el('img', {
                            style: {
                                marginBottom: '10px',
                                maxHeight: '200px',
                                display: !props.metaValue ? 'none' : 'block'
                            },
                            name: 'test-image-class',
                            label: 'Test Class',
                            alt: props.title,
                            src: props.metaValue,
                        }),
                    ),
                    el('div', {},
                        el('strong', {
                                className: `image-size_${random}`,
                            },
                            (props.metaValue) ?  getMeta(props.metaValue) : 'Original image size:'
                        ),
                    ),
                )
            )
        }
    );

    let generateButton = compose.compose(
        withDispatch(function (dispatch, props) {
            return {
                setMetaValue: function (metaValue) {
                    dispatch('core/editor').editPost(
                        {meta: {[props.metaKey]: metaValue}}
                    );
                }
            }
        }),
        withSelect(function (select, props) {
            return {
                postId: select('core/editor').getCurrentPostId()
            }
        }))(function (props) {
            return el(PanelBody,{},

                /*   Modal window   */
                el('div', { className: 'modal' , id: 'openModal'},
                    el('div', { className: 'modal-dialog' },
                        el('div', { className: 'modal-content' },

                            /*   Modal header   */
                            el('div', {className: 'modal-header'},
                                el('span', {className: 'modal-title'},
                                    'Modal title'),
                                el('div', {
                                    className: 'modal-close',
                                    title: 'Close',
                                    onClick: (el) => {
                                        jQuery('#openModal').removeClass('show')
                                    },

                                }, '×')
                            ),

                            /*   Modal content   */
                            el('div', {className: 'modal-body'},
                                el('p', {className: 'modal-body__content'}),
                                'Поздравляю, вы отправили изображения на генерацию, скоро они будут готовы.<br>Вы уже прочитали документацию к плагину? <a target="_blank" href="https://www.notion.so/manuteam/Open-Graph-WordPress-4fb5217a0dda4afd8702c43eb7c91a4d">перейти</a>'
                            ),

                            /*   Modal footer   */
                            el('div', {className: 'modal-footer'},
                                el('button', {
                                    className: 'components-button is-primary modal-button',
                                    onClick: (el) => {
                                        jQuery('#openModal').removeClass('show')
                                    },

                                }, 'Понятно')
                            ),
                        )
                    )
                ),

                /*   Panel with buttons   */
                el('div', { className: 'og-preview_btn_panel'},
                    el('div', { className: 'og-preview__buttons-wrap'},

                        el('div', {className:'og-preview_container'},
                            el('button',{
                                    name: props.postId,
                                    className:'og-preview_button og-preview_button_generate components-button is-primary',
                                }, 'Generate'
                            )
                        ),
                        el('div', {className:'og-preview_container oggwc-dropdown-menu'},
                            el('button',{
                                    name: props.postId,
                                    className:'og-preview_button components-button is-primary oggwc-menu-btn',
                                    onClick: () => {
                                        let dropdownBtn = document.querySelector('.oggwc-menu-btn');
                                        let menuContent = document.querySelector('.menu-content');
                                        let btnToggle = document.querySelector('.dd-button')

                                        if(menuContent.style.display===""){
                                            menuContent.style.display="block";
                                        } else {
                                            menuContent.style.display="";
                                        }
                                        const meta_social = (wp.data.select( 'core/editor' ).getEditedPostAttribute('meta'))

                                        if (!meta_social.oggwc_meta_vk || meta_social.oggwc_meta_vk == '') {
                                            document.getElementsByClassName('vk')[0].style.display = 'none';
                                        }
                                        if (!meta_social.oggwc_meta_fb || meta_social.oggwc_meta_fb == '') {
                                            document.getElementsByClassName('fb')[0].style.display = 'none';
                                        }
                                        if (!meta_social.oggwc_meta_tw || meta_social.oggwc_meta_tw == '') {
                                            document.getElementsByClassName('tw')[0].style.display = 'none';
                                        }

                                    }
                                }, 'Clear cache'
                            ),

                        ),
                        el('div', {className:'og-preview_container'},
                            el('button',{
                                    name: props.postId,
                                    className:'og-preview_button components-button is-primary',
                                    onClick: () => {
                                        window.location.href = '/wp-admin/options-general.php?page=open-graph-wp'
                                    }
                                }, 'Customize'
                            )
                        ),
                    ),

                    /*   request text result   */
                    el('div', {className:'og-preview_container og-preview_container_text-result'},
                        el('span',{className:'og-preview__request-result'})
                    ),

                )
            )

        }

    );

    registerPlugin('oggwc-preview', {
        render: function () {
            return el(Fragment, {},
                el(PluginSidebarMoreMenuItem,
                    {
                        target: 'oggwc-preview',
                    },
                    'Open Graph Preview'
                ),
                el(PluginSidebar,
                    {
                        name: 'oggwc-preview',
                        title: 'Open Graph Preview',
                    },
                    el(generateButton, {},),
                    el('div', {className: 'menu-content'},
                        el('a',{
                            href: 'https://dev.vk.com/method/pages.clearCache', className:'oggwc-links vk'}, 'VK'),
                        el('a',{
                            href: 'https://developers.facebook.com/tools/debug/?q='+ window.location.href, className:'oggwc-links fb'}, 'Facebook'),
                        el('a',{
                            href: ''+ window.location.href, className:'oggwc-links tw'},'Twitter'),
                    ),

                    el(MenuItem, {
                        className: 'oggwc-arrowDown',
                        style:{borderBottom: '1px solid #f0f0f0'},
                        onClick: function (element) {
                            if (element['target'].classList.contains("oggwc-arrowDown")) {
                                element['target'].classList.remove("oggwc-arrowDown");
                                element['target'].classList.add("oggwc-arrowUp");
                            } else {
                                element['target'].classList.remove("oggwc-arrowUp");
                                element['target'].classList.add("oggwc-arrowDown");
                            }
                            var v = document.getElementsByClassName('vk_meta_preview')[0];
                            if( v.style.display != 'block' ){
                                v.style.display = 'block';
                            } else {
                                v.style.display = 'none'
                            }
                        }
                    },'VK' ),

                    el(MetaTextControl,
                        {
                            metaKey: 'oggwc_meta_vk',
                            class: 'vk_meta_preview',
                        }
                    ),
                    el(MenuItem, {
                            className: 'oggwc-arrowDown',
                            onClick: function (element) {
                                if (element['target'].classList.contains("oggwc-arrowDown")) {
                                    element['target'].classList.remove("oggwc-arrowDown");
                                    element['target'].classList.add("oggwc-arrowUp");
                                } else {
                                    element['target'].classList.remove("oggwc-arrowUp");
                                    element['target'].classList.add("oggwc-arrowDown");
                                }
                                var v = document.getElementsByClassName('fb_meta_preview')[0];
                                if( v.style.display != 'block' ){
                                    v.style.display = 'block';
                                } else {
                                    v.style.display = 'none'
                                }
                            }
                        },'Facebook / Twitter'
                    ),
                    el(MetaTextControl,
                        {
                            class: 'fb_meta_preview',
                            metaKey: 'oggwc_meta_fb',
                            title: 'Facebook / Twitter',
                        }
                    ),

                )
            );
        }
    });
})(
    window.wp.plugins,
    window.wp.editPost,
    window.wp.element,
    window.wp.components,
    window.wp.data,
    window.wp.compose
);

