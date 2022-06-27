<template>
  <div class="settings" :style="cssVars">
    <div class="content-block-item">
      <div class="content-block-item__title content-block-item__title_min">
        {{ __('Settings', 'oggwc') }}
      </div>

      <!--   Socials   -->
      <div class="content-block-item__text socials__wrap">

        <div class="content-block-item__subtitle">
          {{ __('Social_Network', 'oggwc') }}:
        </div>
        <div class="socials__items-wrap">

          <!--   VK Checkbox   -->
          <label class="socials__item-wrap">
            <div class="socials__item-left-side">
              <div class="socials__item-icon">
                <unicon name="vk" fill="var(--content-block-text-color)"/>
              </div>
              <span class="class socials__item-text">{{ __('Vkontakte', 'oggwc') }}</span>
            </div>
            <b-form-checkbox class="socials__item-switch"
                             id="checkboxVk"
                             name="checkboxVk"
                             v-model="vk_checkbox_status"
                             value="true"
                             unchecked-value="false"
                             switch
                             size="lg"
                             @change="clickSwitch('Vkontakte')"
            >
            </b-form-checkbox>
          </label>

          <!--   Facebook Checkbox   -->
          <label class="socials__item-wrap">
            <div class="socials__item-left-side">
              <div class="socials__item-icon">
                <unicon name="facebook-f" fill="var(--content-block-text-color)"/>
              </div>
              <span class="class socials__item-text">{{ __('Facebook', 'oggwc') }}</span>
            </div>
            <b-form-checkbox class="socials__item-switch"
                             id="checkboxFb"
                             name="checkboxFb"
                             v-model="fb_checkbox_status"
                             value="true"
                             unchecked-value="false"
                             switch
                             size="lg"
                             @change="clickSwitch('Facebook')"
            >
            </b-form-checkbox>
          </label>

          <!--   Twitter Checkbox   -->
          <label class="socials__item-wrap">
            <div class="socials__item-left-side">
              <div class="socials__item-icon">
                <unicon name="twitter" fill="var(--content-block-text-color)"/>
              </div>
              <span class="class socials__item-text">{{ __('Twitter', 'oggwc') }}</span>
            </div>
            <b-form-checkbox class="socials__item-switch"
                             id="checkboxTw"
                             name="checkboxTw"
                             v-model="tw_checkbox_status"
                             value="true"
                             unchecked-value="false"
                             switch
                             size="lg"
                             @change="clickSwitch('Twitter')"
            >
            </b-form-checkbox>
          </label>
        </div>
      </div>

      <!--   Preview Settings   -->
      <div class="content-block-item__text">
        <div class="content-block-item__subtitle">
          {{ __('Preview_Settings', 'oggwc') }}:
        </div>
        <b-form-group>
          <!--     manual checkbox     -->
          <!-- TODO create manual drag&drop -->
          <!--label class="settings__preview-manual-wrap">
              <div class="settings__preview-manual-text-wrap">
                  <span class="settings__preview-text">{{ __( 'Manual Generation', 'oggwc' ) }}</span>
              </div>
              <b-form-checkbox class="settings__preview-switch"
                               id="manual-checkbox"
                               name="manual-checkbox"
                               v-model="manualStatus"
                               value="true"
                               unchecked-value="false"
                               switch
                               size="lg"
              >
              </b-form-checkbox>
          </label-->

          <!--     Logo switcher     -->
          <label class="settings__preview-logo-switch-wrap">
            <div class="settings__preview-logo-switch-text-wrap">
              <span class="settings__preview-text">{{ __('Logo_enable', 'oggwc') }}</span>
            </div>
            <b-form-checkbox class="settings__preview-switch"
                             id="logo-switch-checkbox"
                             name="logo-switch-checkbox"
                             v-model="logoSwitchStatus"
                             switch
                             size="lg"
            >
            </b-form-checkbox>
          </label>
          <label class="settings__preview-logo-switch-wrap">
            <div class="settings__preview-logo-switch-text-wrap">
              <span class="settings__preview-text">{{ __('Title_enable', 'oggwc') }}</span>
            </div>
            <b-form-checkbox class="settings__preview-switch"
                             id="title-switch-checkbox"
                             name="title-switch-checkbox"
                             v-model="titleSwitchStatus"
                             switch
                             size="lg"
            >
            </b-form-checkbox>
          </label>

          <!--     Logo Image File     -->
          <div class="uploadImage__main-wrap"
               v-if="logoSwitchStatus"
          >
            <div class="content-block-item__subtitle">
              {{ __('Upload_logo', 'oggwc') }}:
            </div>
            <div class="uploadImage__inner-wrap">
              <div class="loader-animate__image d-flex justify-content-center align-items-center"
                   v-if="isLoaderImageShow"
              >
                <b-spinner label="Loading..."></b-spinner>
              </div>


              <div class="uploadImage__left-side"
                   v-show="!isLogoLoad && !logoImg && !isLoaderImageShow"
              >
                <div class="uploadImage__dropzone"
                     @dragenter="onUploadImageDragEnter"
                     @dragleave="onUploadImageDragLeave"
                     @dragover.prevent
                     @drop="onUploadImageDrop"
                     :class="{ dragging: isDragging }"
                >
                  <div class="uploadImage__icon">
                    <unicon name="image-plus" width="50px" height="50px"
                            fill="var(--content-block-text-color)"/>
                  </div>
                  <div class="uploadImage__title">{{ __('Drop_file', 'oggwc') }}</div>
                  <div class="uploadImage__subtitle">{{ __('or', 'oggwc') }}
                    <div class="uploadImage__fileInput">
                      <b-button
                          class="uploadImage__fileInput-wrap button__custom button__custom_blue">
                        {{ __('Choose_File', 'oggwc') }}
                        <label for="fileChoose"></label>
                        <input type="file" ref="file" id="fileChoose"
                               class="uploadImage__subtitle-choose" accept="image/*"
                               @change="clickFile"
                        >
                      </b-button>

                    </div>
                  </div>
                  <div class="uploadImage__error"
                       v-if="fileUploadError"
                  >{{ __('Error_again', 'oggwc') }}
                  </div>
                </div>

              </div>
              <div class="uploadImage__right-side"
                   v-if="isRawImgInfoShow && !isLoaderImageShow"
              >
                    <!--        Do not delete          -->
<!--                v-if="images.length && !fileUploadError && !logoImg"-->


                <div class="uploadImage__preview-title">
                  {{ __('Preview', 'oggwc') }}
                </div>
                <div class="uploadImage__preview-wrap">
                  <img class="uploadImage__preview-img" :src="images[images.length-1]" alt=""
                       draggable="false"
                  >
                  <div class="uploadImage__filename"
                       v-if="images.length && !fileUploadError"
                  >{{ __('Your_File', 'oggwc') }}: {{ images.length ? files[images.length - 1].name : '' }}
                  </div>
                  <div class="uploadImage__preview-btns">
<!--                    <button class="uploadImage__btn-save button__custom button__custom_blue btn-secondary"
                            @click="saveImage"
                            v-if="images.length && !fileUploadError"
                    >{{ __('Save Image', 'oggwc') }}
                    </button>-->
<!--                    <button class="uploadImage__btn-delete button__custom button__custom_blue btn-secondary"
                            @click="deleteImage"
                    >{{ __('Replace Image', 'oggwc') }}
                    </button>-->
                  </div>
                </div>
              </div>

              <div class="uploadImage__right-side"
                   v-if="!images.length && logoImg && !isLoaderImageShow"
              >
                <div class="uploadImage__preview-title">
                  {{ __('Preview', 'oggwc') }}
                </div>
                <div class="uploadImage__preview-wrap">
                  <img class="uploadImage__preview-img" :src="logoImg" alt=""
                       draggable="false"
                  >
                  <div class="uploadImage__preview-btns">
<!--                    <button class="uploadImage__btn-save button__custom button__custom_blue btn-secondary"
                            @click="saveImage"
                    >{{ __('Save Image', 'oggwc') }}
                    </button>-->
                    <button class="uploadImage__btn-replace button__custom button__custom_blue btn-secondary"
                            @click.prevent="$refs.file.click()"
                    >{{ __('Replace_Image', 'oggwc') }}
                    </button>

                    <button class="uploadImage__btn-delete button__custom button__custom_blue btn-secondary"
                            @click="deleteImage"
                    >{{ __('Delete_Image', 'oggwc') }}
                    </button>

                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="preview__params">

            <!--     Title options wrap       -->
            <div class="preview__section-options-wrap preview__section-options-wrap_title"
                 v-if="titleSwitchStatus"
            >

              <div class="preview__section-options-title">
                {{ __('Title_settings', 'oggwc') }}
              </div>

              <div class="preview__section-options-inner">

                <!--    Text color    -->
                <div class="preview__section-option preview__section-option_text-color">
                    <label class="content-block-item__subtitle" for="color-text">{{ __('Text_Color', 'oggwc') }}</label>
                    <b-form-input id="color-text" v-model="textColor" type="color"
                                  class="preview__colorPicker"></b-form-input>
                </div>

                <div class="preview__section-option preview__section-option_text-font">
                  <!--    Font    -->
                    <label class="content-block-item__subtitle"
                           for="select-text-font">{{ __('Font', 'oggwc') }}</label>

                    <b-form-select id="select-text-font" name="select-text-font"
                                   v-model="selectTextFont"
                                   :options="selectTextFontOptions"
                                   @change="setTextStyle"
                    ></b-form-select>
                </div>

                <div class="preview__section-option preview__section-option_text-size">
                  <!--    Font size    -->
                    <label class="content-block-item__subtitle"
                           for="select-text-size">{{ __('Font_size', 'oggwc') }}</label>

                    <b-form-select id="select-text-size" name="select-text-size"
                                   v-model="selectTextFontSize"
                                   :options="selectTextFontSizeOptions"
                                   @change="setTextStyle"
                    ></b-form-select>
                </div>

                <div class="preview__section-option preview__section-option_text-align">
                  <!--    title alignment    -->
                    <label class="content-block-item__subtitle"
                           for="select-text-align">{{ __('Positioning', 'oggwc') }}</label>

                    <b-form-select id="select-text-align" name="select-text-align"
                                   v-model="selectTextAlignment"
                                   :options="selectTextAlignmentOptions"
                                   @change="setTextStyle"
                    ></b-form-select>
                </div>

              </div>

            </div>

            <!--     Other options wrap       -->
            <div class="preview__section-options-wrap preview__section-options-wrap_other">

              <div class="preview__section-options-title">
                {{ __('Other_settings', 'oggwc') }}
              </div>

              <div class="preview__section-options-inner">

                <!--    Background opacity color    -->
                <div class="preview__section-option preview__section-option_bg-color">
                    <label class="content-block-item__subtitle" for="color-bg">{{ __('Opacity_Color', 'oggwc') }}</label>
                    <b-form-input id="color-bg" v-model="bgColor" type="color"
                                  class="preview__colorPicker"></b-form-input>
                </div>

                <!--    Background opacity    -->
                <div class="preview__section-option preview__section-option_text-color">
                    <label class="content-block-item__subtitle"
                           for="range-bg-opacity">{{ __('Background_Opacity', 'oggwc') }}</label>
                    <b-form-input id="range-bg-opacity" v-model="bgOpacity" type="range" min="0" step="0.1"
                                  max="1"></b-form-input>
                </div>

              </div>


            </div>

          </div>

        </b-form-group>
      </div>

      <!--   Preview  -->
      <div class="content-block-preview-image">
        <span class="content-block-item__subtitle content-block-item__subtitle_positions">{{
            __('How_look', 'oggwc')
          }}</span>

        <div class="preview-image_wrap">
          <div class="preview-image_container">
            <div class="preview-image_background"
                 :style="{ 'background-image': 'url(' + getPreviewPostElement('thumbnail_url') + ')' }"
                 :class="[previewStatus]"
            >
              <div class="preview-image_text" v-if="titleSwitchStatus"
                   :class="styleOptionFont"
                   :style="{ 'color': textColor,
                              'font-size': selectTextFontSize + 'em',
                              'text-align': selectTextAlignment
                              }"
              > {{ getPreviewPostElement('name') }}
              </div>
              <div class="preview-image_logo" v-if="logoSwitchStatus"
                   :style="{ 'background-image': 'url(' + logoImg + ')' }"
              ></div>
            </div>

            <div class="preview-image_group">
              <div class="preview-image_group_title">
                {{ getPreviewPostElement('name') }}
              </div>
              <div class="preview-image_group_domain">
                {{ getDomain() }}
              </div>
            </div>
          </div>
        </div>
        <slick v-show="false" class="preview-templates" ref="slick" :options="slickOptions">
          <div class="preview-templates-slide"
               v-for="template in previewTemplates"
               :key="template.image"
               :class="{
                         'active': previewStatus === template.name
                       }"
               @click="selectTemplate(template)"
          >
            <img :src="template.image" alt="">
          </div>
        </slick>
      </div>

      <!--   Position Settings   -->
      <div class="content-block-item__text"
           v-show="manualStatus && disablePositionBlock"
      >
        <span class="content-block-item__subtitle content-block-item__subtitle_positions">{{
            __('Positions', 'oggwc')
          }}</span>

        <div class="position-settings__content">
          <div class="position-settings__tabs-wrap">
            <div class="position-settings__tabs-inner">
              <b-tabs content-class="mt-3" v-model="tabIndex">
                <b-tab title="Vkontakte"
                       class="position-settings__tab-item"
                       @click="clickTab('Vkontakte')"
                       :disabled="vk_checkbox_status  === 'false'"
                       :active="vk_checkbox_status  === 'true'"
                ></b-tab>
                <b-tab title="Facebook"
                       class="position-settings__tab-item"
                       @click="clickTab('Facebook')"
                       :disabled="fb_checkbox_status  === 'false'"
                       :active="fb_checkbox_status  === 'true'"
                ></b-tab>
                <b-tab title="Twitter"
                       class="position-settings__tab-item"
                       @click="clickTab('Twitter')"
                       :disabled="tw_checkbox_status === 'false'"
                       :active="tw_checkbox_status === 'true'"
                ></b-tab>
              </b-tabs>
            </div>
            <div class="text-example__wrap">
              <b-input-group class="example-text__wrap">
                <b-form-input v-model="exampleText" placeholder="Example Text"
                              id="text-example"
                              class="custom__input custom__input_example-text"
                >
                </b-form-input>
                <b-icon icon="x"
                        class="text-example__icon"
                        @click="exampleText = ''"
                        v-if="exampleText !== ''"
                />
              </b-input-group>

            </div>
          </div>

          <!--    drag and drop area    -->
          <div class="dd-area"
               :style="{'background-color': bgColor,
                        'width': areaSize.width,
                        'height': areaSize.height
               }"
          >
            <div class="dd-area__text draggable-item no-select"
                 :style="{'color': textColor}"
            >
              {{ exampleText }}
            </div>
            <div class="dd-area__logo-wrap draggable-item no-select"
                 :class="{'logo-choosen': images.length}"
                 :style="images.length ? {'height': fixLogoHeight,
                          'width': fixLogoWidth} : ''"
                 v-show="logoSwitchStatus === 'true'"

            >

              <div class="dd-area__logo-text"
                   v-if="!images.length"
              >
                <div class="dd-area__logo-icon">
                  <unicon name="image" fill="#ffffff" width="36" height="36"/>
                </div>
                {{ __('Logo', 'oggwc') }}
              </div>
              <img :src="images[images.length-1]" alt="" class="dd-area__logo no-select"
                   draggable="false"
                   @load="imgLoad"
                   v-if="images.length && !fileUploadError"
                   :style="images.length ? {'height': fixLogoHeight,
                          'width': fixLogoWidth} : ''"
              >
            </div>
            <div class="dd-area__size-title">{{ __('Area_size', 'oggwc') }}
              {{ `${areaSize.width.replace('px', '')} x ${areaSize.height.replace('px', '')}` }}
            </div>
          </div>

        </div>
      </div>
      <b-button class="button__save mt-3 mb-3 button__custom button__custom_blue"
                @click="settingsSave"
      >{{ __('Save', 'oggwc') }}
      </b-button>

    </div>

  </div>
</template>

<script>
import axios from 'axios'
import Mixin from '../../../mixin'
import  'jquery';
import Slick from 'vue-slick';
import previewTemplates1 from '../../../imgs/template-1.png'
import previewTemplates2 from '../../../imgs/template-2.png'
import previewTemplates3 from '../../../imgs/template-3.png'
import previewTemplates4 from '../../../imgs/template-4.png'

export default {
  name: "Settings",
  mixins: [Mixin],
  components: { Slick },
  props: {},
  data: () => ({
    tabIndex: 1,
    isLogoLoad: false,

    tabs: [],
    vk_checkbox_status: false,
    fb_checkbox_status: false,
    tw_checkbox_status: false,
    disablePositionBlock: false,
    manualStatus: false,
    logoSwitchStatus: false,
    titleSwitchStatus: false,
    logoFile: null,
    logoImg: null,
    bgColor: '#dddddd',
    textColor: '#fff',
    bgOpacity: 0.1,
    exampleText: 'Example Text',

    selectTextFont: '',
    selectTextFontOptions: [],
    selectTextFontSize: '',
    selectTextFontSizeOptions: [],
    selectTextAlignment: 'left',
    selectTextAlignmentOptions: ['left', 'center', 'right'],

    styleOptionFont: '',
    styleOptionSize: '',
    styleOptionAlign: '',

    isLoaderImageShow: false,
    isRawImgInfoShow: false,
    isDragging: false,
    dragCount: 0,
    isDraggable: '',
    fileUploadError: false,
    fixLogoHeight: '',
    fixLogoWidth: '',

    textCoords: {
      vk: {
        x: '',
        y: ''
      },
      fb: {
        x: '',
        y: ''
      },
      tw: {
        x: '',
        y: ''
      }
    },
    logoCoords: {
      vk: {
        x: '',
        y: ''
      },
      fb: {
        x: '',
        y: ''
      },
      tw: {
        x: '',
        y: ''
      }
    },

    tabActive: '',
    stockAreaSizes: {
      vk: {
        width: 537,
        height: 240
      },
      fb: {
        width: 476,
        height: 248
      },
      tw: {
        width: 600,
        height: 315
      }
    },
    areaSize: {
      width: '',
      height: ''
    },

    images: [],
    files: [],
    posts: [],
    oavar: [],

    slickOptions: {
      slidesToShow: 4,
      arrows: false,
      infinite: false,
    },
    previewStatus: '',
    previewTemplates: [
      {
        name: 'template1',
        image: previewTemplates1,
      },
      {
        name: 'template2',
        image: previewTemplates2,
      },
      {
        name: 'template3',
        image: previewTemplates3,
      },
      {
        name: 'template4',
        image: previewTemplates4,
      }
    ]
  }),
  computed: {
    cssVars() {
      return {
        '--bg-preview-opacity': this.bgOpacity,
        '--bg-preview-color': this.bgColor,
      }
    }
  },
  watch: {
    logoSwitchStatus: function (value) {
      if (!value) {
        this.logoImg = null;
      }
    },
  },
  mounted() {
    // Move Text Element
    let blockElems = document.querySelectorAll('.draggable-item'),
        wrapElem = document.querySelector('.dd-area');

    let coords = {};
    blockElems.forEach(blockElem => {


      blockElem.draggable = false;

      const measureElem = (elem, event) => {
        const measures = elem.getBoundingClientRect();
        const borderWidth = parseInt(getComputedStyle(elem)["border-width"]) * 2;

        return {
          offsetTop: measures.top,
          offsetLeft: measures.left,
          width: measures.width - borderWidth,
          height: measures.height - borderWidth,
          clickedX: event.layerX,
          clickedY: event.layerY,
          border: borderWidth
        }
      }

      const setupMeasures = e => {
        wrapElem.classList.add('allow-drag');
        blockElem.classList.add('active');

        coords.wrap = measureElem(wrapElem, e);
        coords.block = measureElem(blockElem, e);
      }

      const checkEdges = (block, wrap, fn) => {
        let x = block.x;
        let y = block.y;

        if (x < 0) x = 0;
        if (y < 0) y = 0;

        if (x > wrap.width - block.width) {
          x = wrap.width - block.width
        }

        if (y > wrap.height - block.height) {
          y = wrap.height - block.height;
        }

        fn.call(this, x, y);

      }

      const drawBlock = (x, y) => {
        blockElem.style.top = `${y}px`;
        blockElem.style.left = `${x}px`;
      }

      const setupBlockPosition = e => {
        if (blockElem.classList.contains("active") === false) return;
        if (wrapElem.classList.contains("allow-drag") === false) return;
        const {block, wrap} = coords;
        block.x = e.clientX - wrap.offsetLeft - block.clickedX - wrap.border;
        block.y = e.clientY - wrap.offsetTop - block.clickedY - wrap.border;
        checkEdges(block, wrap, drawBlock);
      };

      const cancelDrag = () => {
        wrapElem.classList.remove('allow-drag');
        blockElem.classList.remove('active');
        if (blockElem.classList.contains("dd-area__text")) {
          if (this.tabActive === 'Vkontakte') {
            this.textCoords.vk.x = blockElem.style.left;
            this.textCoords.vk.y = blockElem.style.top;
          }
          if (this.tabActive === 'Facebook') {
            this.textCoords.fb.x = blockElem.style.left;
            this.textCoords.fb.y = blockElem.style.top;
          }
          if (this.tabActive === 'Twitter') {
            this.textCoords.tw.x = blockElem.style.left;
            this.textCoords.tw.y = blockElem.style.top;
          }

        } else {
          this.logoCoords.vk.x = blockElem.style.left;
          this.logoCoords.vk.y = blockElem.style.top;
        }

      }
      blockElem.addEventListener('mousedown', setupMeasures);
      document.addEventListener('mousemove', setupBlockPosition);
      document.addEventListener('mouseup', cancelDrag);
    })

    // console.log(this.tw_checkbox_status)
    // console.log(this.fb_checkbox_status)
    // console.log(this.vk_checkbox_status)

    if (this.tabs.length > 0) {
      this.clickTab(this.tabs[0])
    }

    /*  Set text settings selects  */
    this.selectTextFontOptions = window.oggwc_admin.font_family.map(item => item.name.replace('.ttf',''))
    this.oavar.oggwc_title_font_family ? this.selectTextFont = this.oavar.oggwc_title_font_family : this.selectTextFont = this.selectTextFontOptions[0]

    this.selectTextFontSizeOptions = window.oggwc_admin.font_sizes
    this.oavar.oggwc_title_font_size ? this.selectTextFontSize = this.oavar.oggwc_title_font_size : this.selectTextFontSize = this.selectTextFontSizeOptions[0]

    this.oavar.oggwc_text_position ? this.selectTextAlignment = this.oavar.oggwc_text_position : this.selectTextAlignment = 'left'

    console.log(this.oavar)


  },
  // Created
  created() {
    // init global js WP var
    this.oavar = window.oggwc_admin;

    this.vk_checkbox_status = this.oavar.oggwc_soc_vk;
    this.vk_checkbox_status === 'true' ? this.tabs.push('Vkontakte') : ''
    this.fb_checkbox_status = this.oavar.oggwc_soc_fb;
    this.fb_checkbox_status === 'true' ? this.tabs.push('Facebook') : ''
    this.tw_checkbox_status = this.oavar.oggwc_soc_tw;
    this.tw_checkbox_status === 'true' ? this.tabs.push('Twitter') : ''
    this.oggwc_sizes = this.oavar.oggwc_sizes;
    this.manualStatus = this.oavar.oggwc_manuals_generator;
    this.bgColor = this.oavar.oggwc_default_color;

    let coordTextVK = this.oavar.oggwc_soc_vk_text_coords !== null ? this.oavar.oggwc_soc_vk_text_coords.replaceAll('\\', '') : '{}';
    let coordLogoVK = this.oavar.oggwc_soc_vk_text_coords !== null ? this.oavar.oggwc_soc_vk_text_coords.replaceAll('\\', '') : '{}';

    this.textCoords.vk = JSON.parse(coordTextVK);
    this.logoCoords.vk = JSON.parse(coordLogoVK);

    this.oggwc_soc_fb_text_coords = this.oavar.oggwc_soc_fb_text_coords;
    this.oggwc_soc_fb_logo_coords = this.oavar.oggwc_soc_fb_logo_coords;
    this.oggwc_soc_tw_text_coords = this.oavar.oggwc_soc_tw_text_coords;
    this.oggwc_soc_tw_logo_coords = this.oavar.oggwc_soc_tw_logo_coords;
    this.logoImg = this.oavar.oggwc_logo_img == 'null' ? null : this.oavar.oggwc_logo_img;
    this.titleSwitchStatus = this.oavar.oggwc_title_enable
    this.textColor = this.oavar.oggwc_default_text_color
    this.bgOpacity = this.oavar.oggwc_bg_opacity

    this.posts = this.oavar.oggwc_preview_posts

    if (this.logoImg !== null) {
      this.logoSwitchStatus = true;
    }

    this.previewStatus = this.oavar.oggwc_template_type || 'template1'
  },
  // Methods
  methods: {
    selectTemplate(template) {
      this.previewStatus = template.name
    },

    setTextStyle() {
      this.styleOptionSize = 'option-name_' + this.selectTextFont + '_' + this.selectTextFontSize
      this.styleOptionFont = `font_family_${this.selectTextFont}`
    },

    getPreviewPostElement(param) {
      if (!this.posts.length) {
        return false;
      }

      /*
      params:
      - ID
      - name
      - url
      - soc_vk
      - soc_fb
      - soc_tw
      - thumbnail_url
       */

      return this.posts[0][param]

    },
    imgLoad(e) {
      let img = new Image(),
          blockHeight = '240px',
          blockWidth = '476px';
      img = e.target
      let w = img.naturalWidth,
          h = img.naturalHeight;

      if (h > w || h === w) {
        if (h > +blockHeight.replace(/px/, '')) {
          this.fixLogoHeight = blockHeight
        } else if (h < +blockHeight.replace(/px/, '')) {
          this.fixLogoHeight = h + 'px'
        }
        this.fixLogoWidth = 'auto'
      } else {
        if (w > +blockWidth.replace(/px/, '') && h > +blockHeight.replace(/px/, '')) {
          this.fixLogoWidth = 'auto'
          this.fixLogoHeight = blockHeight
          // if ( h > +blockHeight.replace(/px/, '') ) {
          //   this.fixLogoWidth = 'auto'
          //   this.fixLogoHeight = blockHeight
          // } else {
          //   this.fixLogoWidth = blockWidth
          //   this.fixLogoHeight = 'auto'
          // }
        } else if (w < +blockWidth.replace(/px/, '')) {
          if (h > +blockHeight.replace(/px/, '')) {
            this.fixLogoWidth = 'auto'
            this.fixLogoHeight = blockHeight
          } else {
            this.fixLogoWidth = w + 'px'
            this.fixLogoHeight = 'auto'
          }
        } else {
          this.fixLogoWidth = blockWidth
          this.fixLogoHeight = 'auto'
        }
      }

    },

    deleteImage() {
      this.isLoaderImageShow = true
      this.images.splice(0, this.images.length)
      this.files.splice(0, this.files.length)
      this.isLogoLoad = false
      this.logoImg = null;
      let params = new URLSearchParams(),
          that = this;
      params.append('action', 'oggwc_delete_logo');
      axios
          .post(that.oavar.ajax_url, params)
          .then((response) => {
            console.log(response)
            this.isLoaderImageShow = false
          })
          .catch(error => {
            console.log(error);
            this.isLoaderImageShow = false
          })
    },

    saveImage() {
      this.isLoaderImageShow = true
      let that = this;
      let formData = new FormData();
      formData.append('file', this.logoFile);
      formData.append('action', "oggwc_upload_logo");
      axios
          .post(that.oavar.ajax_url, formData)
          .then((response) => {
            console.log('Save image action: ')
            console.log(response.data)
            if (undefined !== response.data.img_url) {
              this.logoImg = response.data.img_url
              this.images = []
            }
            this.isLoaderImageShow = false
          })
          .catch(error => {
            console.log(error);
            this.isLogoLoad = false
            this.logoImg = null
            this.fileUploadError = true
            this.isLoaderImageShow = false
          })
    },

    clickSwitch(social) {
      if (social === 'Vkontakte') {
        this.areaSize.width = `${this.stockAreaSizes.vk.width}px`
        this.areaSize.height = `${this.stockAreaSizes.vk.height}px`
        this.tabActive = social
        this.tabIndex = 0
        this.tabs.push(social)

      } else if (social === 'Facebook') {
        this.areaSize.width = `${this.stockAreaSizes.fb.width}px`
        this.areaSize.height = `${this.stockAreaSizes.fb.height}px`
        this.tabActive = social;
        this.tabs.push(social)
        this.tabIndex = 1

      } else if (social === 'Twitter') {
        this.areaSize.width = `${this.stockAreaSizes.tw.width}px`
        this.areaSize.height = `${this.stockAreaSizes.tw.height}px`
        this.tabActive = social;
        this.tabIndex = 2
        this.tabs.push(social)

      } else {

        let idx = this.tabs.findIndex(item => item === social)
        if (idx !== -1) {
          this.tabs.splice(idx, 1)
          if (this.tabs.length > 0) {
            this.tabActive = this.tabs[0]
            this.clickTab(this.tabActive)
          }
        }

      }

    },
    /**
     * Save all settings
     */
    settingsSave() {
      this.$store.state.loaderIsShow = true
      let params = new URLSearchParams(),
          that = this;
      params.append('action', 'oggwc_update_admin_settings');
      params.append('oggwc_logo_img', this.logoImg);
      params.append('oggwc_title_enable', this.titleSwitchStatus);
      params.append('oggwc_soc_vk', this.vk_checkbox_status);
      params.append('oggwc_soc_fb', this.fb_checkbox_status);
      params.append('oggwc_soc_tw', this.tw_checkbox_status);
      params.append('oggwc_sizes', JSON.stringify(this.stockAreaSizes));
      params.append('oggwc_manuals_generator', this.manualStatus);
      params.append('oggwc_default_color', this.bgColor);
      params.append('oggwc_default_text_color', this.textColor);
      params.append('oggwc_bg_opacity', this.bgOpacity);
      params.append('oggwc_title_font_family', this.selectTextFont);
      params.append('oggwc_title_font_size', this.selectTextFontSize);
      params.append('oggwc_text_position', this.selectTextAlignment);


      params.append('oggwc_soc_vk_text_coords', JSON.stringify(this.textCoords.vk));
      params.append('oggwc_soc_vk_logo_coords', JSON.stringify(this.logoCoords.vk));
      params.append('oggwc_soc_fb_text_coords', JSON.stringify(this.textCoords.fb));
      params.append('oggwc_soc_fb_logo_coords', JSON.stringify(this.logoCoords.fb));

      params.append('oggwc_soc_tw_text_coords', JSON.stringify(this.textCoords.tw));
      params.append('oggwc_soc_tw_logo_coords', JSON.stringify(this.logoCoords.tw));

      params.append('oggwc_template_type', this.previewStatus)

      axios
          .post(that.oavar.ajax_url, params)
          .then((response) => {
            console.log('response', response)
            this.$store.state.loaderIsShow = false
          })
          .catch(error => {
            console.log(error);
            this.$store.state.loaderIsShow = false
          })
    },
    getDomain() {
      return document.location.hostname
    },
    /**
     * switch preview builder area
     * @param tab
     */
    clickTab(tab) {
      this.tabActive = tab;
      if (tab === 'Vkontakte') {
        this.areaSize.width = `${this.stockAreaSizes.vk.width}px`
        this.areaSize.height = `${this.stockAreaSizes.vk.height}px`
        this.tabIndex = 0
      } else if (tab === 'Facebook') {
        this.areaSize.width = `${this.stockAreaSizes.fb.width}px`
        this.areaSize.height = `${this.stockAreaSizes.fb.height}px`
        this.tabIndex = 1
      } else if (tab === 'Twitter') {
        this.areaSize.width = `${this.stockAreaSizes.tw.width}px`
        this.areaSize.height = `${this.stockAreaSizes.tw.height}px`
        this.tabIndex = 2
      }
    },
    clickFile(e) {
      const files = e.target.files;
      //this.logoFile = files[files.length-1];
      this.logoFile = this.$refs.file.files[0];
      Array.from(files).forEach(file => this.addImage(file));

      // let imgSource = document.querySelector('.dd-area__logo');
      // imgSource.src = URL.createObjectURL(event.target.files[0])
    },
    setDraggable() {
    },

    onUploadImageDragEnter(e) {
      e.preventDefault();
      this.dragCount++;
      this.isDragging = true;
    },
    onUploadImageDragLeave(e) {
      e.preventDefault();
      this.dragCount--;
      if (this.dragCount <= 0)
        this.isDragging = false
    },
    onUploadImageDrop(e) {
      e.preventDefault();
      e.stopPropagation();
      this.isDragging = false;
      const files = e.dataTransfer.files;
      Array.from(files).forEach(file => this.addImage(file));
    },
    addImage(file) {
      if (!file.type.match('image.*')) {
        this.fileUploadError = true
        this.isLogoLoad = false
        return;
      }

      this.fileUploadError = false
      this.files.push(file);
      const imgRead = new FileReader();
      imgRead.onload = (e) => this.images.push(e.target.result);
      imgRead.readAsDataURL(file);

      this.saveImage()

      this.isLogoLoad = true
    },
  }
}

</script>

<style scoped>
.socials__wrap {
  margin-bottom: 25px;
}

.socials__items-wrap {
  display: flex;
  flex-direction: row;
  justify-content: flex-start;
  align-items: center;
}

.socials__item-wrap,
.socials__item-left-side {
  display: flex;
  flex-direction: row;
  justify-content: flex-start;
  align-items: center;
}

.socials__item-wrap {
  justify-content: space-between;
  min-width: 150px;
  max-width: 200px;
  width: 100%;
  border: 1px solid #ddd;
  border-radius: 6px;
  padding: 5px 10px;
  margin-bottom: 0;
}

span.class.socials__item-text {
  line-height: 1px;
}

.socials__item-wrap:not(:last-child) {
  margin-right: 20px;
}

.socials__item-switch {
  margin-top: -5px;
  margin-right: -8px;
}

.socials__item-icon {
  margin-right: 10px;
}

.settings__preview-manual-wrap,
.settings__preview-logo-switch-wrap {
  display: flex;
  flex-direction: row;
  justify-content: flex-start;
  align-items: center;
  max-width: 230px;
  margin-bottom: 10px;
}

.settings__preview-logo-switch-wrap {
  margin-bottom: 14px;
}

.settings__preview-manual-text-wrap,
.settings__preview-logo-switch-text-wrap {
  margin-right: 15px;
}

.settings__preview-switch {
  padding: 0;
  margin-bottom: 5px;
}

.fileChoose__wrap {
  display: block;
  max-width: 350px;
  margin-bottom: 20px;
}

.uploadImage__main-wrap {
  margin-bottom: 20px;
}

.uploadImage__inner-wrap {
  display: flex;
  flex-direction: row;
  justify-content: flex-start;
  align-items: flex-start;
}

.uploadImage__left-side {
  margin-right: 20px;
}

.uploadImage__btn-delete,
.uploadImage__btn-replace,
.uploadImage__btn-save {
  display: block;
  margin: 0 auto;
  margin-top: 15px;
}

.uploadImage__preview-title {
  margin-bottom: 15px;
}

img.uploadImage__preview-img {
  max-width: 200px;
  margin-bottom: 10px;
}

.uploadImage__dropzone {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  width: 290px;
  min-height: 150px;
  margin-top: 15px;
  border: 3px dashed var(--content-block-text-color);
  border-radius: 20px;
}

.dragging {
  background-color: rgba(26, 143, 227, .05);
  border-color: var(--first-accent-color);
}

.uploadImage__title {
  font-size: 16px;
  font-weight: 400;
  margin-bottom: 5px;
}

.uploadImage__fileInput-wrap {
  position: relative;
  overflow: hidden;
  cursor: pointer;
  padding: 0 10px;
  margin-left: 10px;
  height: 35px;
}

.uploadImage__fileInput-wrap label,
.uploadImage__fileInput-wrap input {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  opacity: 0;
  cursor: pointer;
  margin: 0;
  padding: 0;
}

.uploadImage__icon {
  margin-bottom: 10px;
}

.uploadImage__subtitle {
  display: flex;
  flex-direction: row;
  justify-content: unset;
  align-items: center;
}

.uploadImage__error {
  margin: 5px;
  color: var(--error);
  font-size: 14px;
  font-weight: 400;
}

label.custom-file-label {
  display: block;
  max-width: 350px;
}

.button__save {
  display: block;
  margin-top: 55px;
}

.preview__params {

}

.preview__params label.content-block-item__subtitle {
  margin-right: 10px;
}

input.preview__colorPicker {
  display: block;
  width: 100px;
}

.preview__section-options-wrap:not(:last-child) {
  margin-bottom: 25px;
}

.preview__section-option {
  display: flex;
  flex-direction: column;
  justify-content: flex-start;
  align-items: flex-start;
  min-width: 200px;
}

.preview__section-option:not(:last-child) {
  margin-right: 30px;
}

.preview__section-options-inner {
  display: flex;
  flex-direction: row;
  justify-content: flex-start;
  flex-wrap: wrap;
}

.preview-templates{
  overflow: hidden;
  max-width: 800px;
  margin: 15px auto;
  padding: 0 5px;
}

.preview-templates-slide {
  cursor: pointer;
}
.preview-templates-slide.active{
  border: 1px solid rgba(0,20,51,.25)
}

.preview-templates-slide img {
  max-width: 100%;
  object-fit: contain;
}

.example-text__wrap {
  position: relative;
  max-width: 350px;
  width: 100%;
  margin-bottom: 0;
}

.text-example__icon {
  position: absolute;
  display: block;
  right: 15px;
  top: 50%;
  transform: translate(0, -50%);
  cursor: pointer;
  fill: var(--text-accent-color-dark);
  width: 24px;
  height: 24px;
  z-index: 10;
  transition: var(--transition-default);
}

.text-example__icon:hover {
  transform: translate(0, -50%) rotate(90deg);
}

.example-text__button-wrap:hover {
  cursor: pointer;
}

#text-example {

}

/*  Position settings */
.position-settings__tab-item {

}

.content-block-item__subtitle_positions {
  display: block;
  margin-bottom: 20px;
}

/*====================*/

.dd-area {
  position: relative;
  width: auto;
  min-height: 240px;
  height: 100%;
  padding: 10px;
  background-color: darkturquoise;
  word-break: break-word;
  margin: 0 auto;
}

.dd-area__size-title {
  position: absolute;
  left: 5px;
  bottom: -25px;
  font-size: 14px;
}

.dd-area__text {
  position: absolute;
  top: 15px;
  left: 70px;
  display: inline-block;
  padding: 10px 15px;
  color: var(--text-accent-color-dark);
  font-size: 14px;
  font-weight: 600;
  line-height: 1.25;
  cursor: move;
  z-index: 15;
}

.dd-area__logo-wrap {
  position: absolute;
  width: 70px;
  height: 70px;
  /*border-radius: 50%;*/
  top: 0;
  left: 0;
  background-color: #72777c;
  /*border: 3px solid #ffffff;*/
  overflow: hidden;
  cursor: move;
  z-index: 10;
}

.dd-area__logo-wrap.logo-choosen {
  border: none;
  background-color: transparent;
}

.dd-area__logo-icon {

}

.dd-area__logo-text {
  width: 100%;
  height: 100%;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  color: #ffffff;
  font-size: 12px;
  font-weight: 400;
  text-transform: uppercase;
}

.dd-area__logo {
  display: block;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: 11;
}

.position-settings__tabs-wrap {
  position: relative;
  display: flex;
  flex-direction: row;
  justify-content: flex-start;
  align-content: center;
}

.position-settings__tabs-inner {
  width: 50%;
}

.position-settings__tab {
  padding: 10px 25px;
  margin: 10px 0;
  border: 1px solid black;
  cursor: pointer;
  min-width: 145px;
  font-size: 16px;
  text-align: center;
}

.position-settings__tab.active {
  background-color: antiquewhite;
  color: black;
  /* font-size: 16px; */
  font-weight: 500;
}

.text-example__wrap {
  width: 50%;
  display: flex;
  flex-direction: row;
  justify-content: center;
  align-items: center;
  margin-bottom: 20px;
}

.preview-image_container {
  display: block;
  max-width: 537px;
  width: 100%;
  outline: 1px solid rgba(0, 20, 51, .12);
  outline-offset: 0;
  position: relative;
  margin: 0 auto;
}

.preview-image_background {
  position: relative;
  width: 100%;
  height: 240px;
  background-size: cover !important;
  font-size: 14px;
}

.preview-image_background:before {
  content: '';
  position: absolute;
  width: 100%;
  height: 100%;
  background: var(--bg-preview-color);
  opacity: var(--bg-preview-opacity);
  background-size: cover;
}

.preview-image_background.template1 .preview-image_text,
.preview-image_background.template2 .preview-image_text{
  top:  32px;
}

.preview-image_background.template3 .preview-image_text,
.preview-image_background.template4 .preview-image_text{
  top: calc(100% - 70px);
}

.preview-image_background.template1 .preview-image_logo{
  right: 35px;
  bottom: 33px;
}

.preview-image_background.template2 .preview-image_logo{
  left: 35px;
  bottom: 33px;
}
.preview-image_background.template3 .preview-image_logo{
  left: 35px;
  top: 33px;
}
.preview-image_background.template4 .preview-image_logo{
  right: 35px;
  top: 33px;
}

.preview-image_text {
  font-size: 22px;
  position: relative;
  width: 100%;
  height: 130px;
  color: #ffffff;
  padding: 0 32px;
}

.preview-image_logo {
  width: 45px;
  height: 45px;
  background-size: cover;
  position: absolute;
}

.preview-image_group_domain {
  font-size: 12.5px;
  color: #939393;
  padding: 5px;
  -webkit-font-smoothing: subpixel-antialiased;
}

.preview-image_group_title {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
  text-overflow: ellipsis;
  max-height: 2.8em;
  text-overflow: ellipsis;
  word-break: break-word;
  color: #000;
  font-size: 16px;
  line-height: 21px;
  font-weight: 400;
  -webkit-font-smoothing: subpixel-antialiased;
  padding: 5px;
}

.preview-image_group {
  padding: 10px 12px;
}

.preview-image_wrap {
  margin-bottom: 45px;
}

.uploadImage__preview-wrap {
  display: flex;
}

.uploadImage__preview-btns {
  padding: 0 30px;
}

.loader-animate__image {
  width: 300px;
  height: 150px;
  margin-top: 15px;
}

</style>