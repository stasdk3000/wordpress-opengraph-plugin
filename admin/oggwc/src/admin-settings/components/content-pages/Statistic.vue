<template>
  <div class="statistic">

    <!--    Modal Window    -->
    <b-modal id="modal-preview"
             centered
             v-model="modalPreviewShow"
             size="lg"
    >
      <template #modal-title>
        {{ __('Preview', 'oggwc') }}
      </template>
      <img :src="modalLink" alt="img" class="statistic__modal-img">

      <template #modal-footer>
        <div class="w-100">
          <b-button
              class="float-right button__custom button__custom_blue"
              @click="modalPreviewShow = false"
          >
            {{ __('Close', 'oggwc') }}
          </b-button>
        </div>
      </template>
    </b-modal>
    <!--  =================================================== -->

    <div class="">
      <div class="content-block-item__title content-block-item__title_min">
        {{ __('Statistics', 'oggwc') }}
      </div>

      <b-row class="statistic__top-panel">
        <!--   Records count   -->
        <b-col cols="3">
          <div class="statistic__records-count">
            <span class="statistic__records-count-text">{{ __('Posts_Count', 'oggwc') }}: </span>
            <span class="statistic__records-count-num">{{ tableData.length }}</span>
          </div>
        </b-col>

        <!--    Search  -->
        <b-col cols="4" offset-md="5">
          <div class="statistic__search-wrap">
            <b-form-input v-model="searchValue" class="statistic__search-input"
                          @input="onSearchType" @blur="isSearchInput = false"
            ></b-form-input>
            <div class="statistic__icon">
              <unicon name="search" fill="var(--content-block-text-color)"/>
            </div>
          </div>
          <span class="statistic__search-invalid-error" v-if="minSearchValue">
            Search string must be min: {{ minSearchLength }} characters long.
          </span>

        </b-col>
        <b-col>
          <div class="statistic__search-error"
               v-if="!tableData.length"
          >
            {{ __('Not Founded', 'oggwc') }}
          </div>
        </b-col>
      </b-row>

      <!--    Table div-->
      <div class="statistic__table-wrap"
           v-if="tableData.length"
      >
        <b-row class="statistic__table-row statistic__table-row_header">
          <!--        <b-col cols="1" class="statistic__table-header statistic__table-header_id">{{ __( 'ID', 'oggwc' ) }}</b-col>-->
          <b-col cols="7" class="statistic__table-header statistic__table-header_name">{{ __('Post', 'oggwc') }}
          </b-col>
          <b-col cols="3" class="statistic__table-header statistic__table-header_socials">
            {{ __('Socials_Preview', 'oggwc') }}
          </b-col>
          <b-col cols="2" class="statistic__table-header statistic__table-header_actions">{{ __('Actions', 'oggwc') }}
          </b-col>
        </b-row>

        <div class="statistic__table-row"
             v-for="(row) in tableData.slice(0, maxRecordsLimit)" :key="row.id"
        >
          <b-row>
            <!--          <b-col cols="1" class="statistic__table-col statistic__table-col_id">{{ index }}{{ row.id }}</b-col>-->
            <b-col cols="7" class="statistic__table-col statistic__table-col_name">
              <div class="statistic__table-row-name">{{ row.name }}</div>
              <div class="statistic__table-row-url">
                <div class="statistic__table-row-url-icon">
                  <unicon name="link" fill="var(--content-block-text-color)" width="14" height="14"/>
                </div>
                <a class="statistic__table-row-url-value" :href="row.url" target="_blank">
                  {{ row.url }}
                </a>
              </div>
            </b-col>
            <b-col cols="3" class="statistic__table-col">
              <div class="statistic__table-row-socials-wrap">
                <div class="statistic__table-row-socials-icon statistic__table-row-socials-icon_vk"
                     title="Preview VK">
                  <unicon name="vk" fill="var(--content-block-text-color)"
                          @click="modalShow(row.soc_vk, 'вконтакте')"
                  />
                </div>
                <div class="statistic__table-row-socials-icon statistic__table-row-socials-icon_fb"
                     title="Preview Facebook">
                  <unicon name="facebook-f" fill="var(--content-block-text-color)"
                          @click="modalShow(row.soc_fb, 'facebook')"
                  />
                </div>
                <div class="statistic__table-row-socials-icon statistic__table-row-socials-icon_tw"
                     title="Preview Twitter">
                  <unicon name="twitter" fill="var(--content-block-text-color)"
                          @click="modalShow(row.soc_tw, 'twitter')"
                  />
                </div>
              </div>
            </b-col>
            <b-col cols="2" class="statistic__table-col">
              <div class="statistic__table-row-actions-wrap">
                <div class="statistic__table-row-actions-icon" title="Обновить">
                  <unicon name="sync" fill="var(--first-accent-color)"/>
                </div>
                <div class="statistic__table-row-actions-icon" title="Удалить">
                  <unicon name="trash-alt" fill="var(--first-accent-color)" width="26" height="26"/>
                </div>
              </div>
            </b-col>
          </b-row>
        </div>


      </div>

      <b-button class="statistics__btn-load-more button__custom button__custom_blue"
                v-if="maxRecordsLimit < tableData.length"
                @click="showMoreRecords"
      >{{ __('Load posts', 'oggwc') }}
      </b-button>

    </div>
  </div>
</template>

<script>
import Mixin from '../../../mixin'

let debounce = require('lodash.debounce');


export default {
  name: "Statistic",
  mixins: [Mixin],
  props: {},
  data: () => ({
    // Max Records shows firstly Limit
    maxRecordsLimit: 15,
    //================================

    debounceDelay: 500,
    minSearchLength: 2,

    isSearchInput: false,
    searchError: false,
    searchValue: '',
    previewType: '',
    modalPreviewShow: false,
    modalLink: '',
    tableData: [],
  }),

  /*  CREATED  */
  created() {
    // this.tableData =  window.oggwc_admin.oggwc_statistic_posts
    this.oavar = window.oggwc_admin
  },

  /*  METHODS  */
  methods: {
    onSearchType() {
      this.isSearchInput = true
      this.searchValue.length >= this.minSearchLength ? this.debouncedSearch() : this.tableData = window.oggwc_admin.oggwc_statistic_posts.slice()
      /*this.searchValue.length > this.minSearchLength ? this.debouncedSearch() : this.tableData.splice(0, this.tableData.length)*/
    },

    /*searchAction() {
      let params = new URLSearchParams(),
          that = this;
      params.append('action', 'oggwc_search_post');
      params.append('s', that.searchValue);
      axios
          .post(that.oavar.ajax_url, params)
          .then((response) => {
            console.log(response)
            that.tableData = response.data.posts
          })
          .catch(error => {
            console.log(error);
          })

    },*/

    // Show more fixed limit records by clicking "show more" button
    showMoreRecords() {
      this.maxRecordsLimit += this.maxRecordsLimit;
    },

    /*  click to show modal window  */
    modalShow(link, type) {
      this.modalPreviewShow = true
      this.modalLink = link
      this.previewType = type
    },

    searchedItem () {
      this.tableData = window.oggwc_admin.oggwc_statistic_posts.slice()
      if ( this.tableData.length ) {
        this.tableData = this.tableData.filter(item =>
            item.name.toLowerCase().indexOf(this.searchValue.toLowerCase()) !== -1)
        console.log(this.tableData)
      }
    },
  },

  /*  MOUNTED  */
  mounted() {
    this.tableData = window.oggwc_admin.oggwc_statistic_posts.slice()
  },

  /*  COMPUTED  */
  computed: {

    minSearchValue() {
      return (this.searchValue.length < this.minSearchLength) && this.isSearchInput
    },

    // Debounce function for typing in notes
    debouncedSearch() {
      return debounce(this.searchedItem, this.debounceDelay)
    },
  },

}
</script>

<style scoped>
/*  Modal  */
.statistic__modal-img {
  width: 100%;
  height: auto;
}

/*  ==================  */
.statistic__top-panel {
  flex-direction: row;
  justify-content: center;
  align-items: center;
  margin-bottom: 25px;
}

.statistic__search-wrap {
  position: relative;
  width: 100%;
}

.statistic__search-input {
  height: 50px;
  padding: 0 55px 0 20px;
  border-radius: 16px;
  background-color: #eaeaea;
  border: none;
  font-size: 14px;
  font-weight: 600;
}

.statistic__search-error {
  text-align: center;
  margin: 25px;
  color: var(--content-block-text-color);
  font-size: 18px;
  font-weight: 400;
}

.statistic__icon {
  position: absolute;
  top: 50%;
  right: 20px;
  transform: translate(0, -50%);
}

.statistic__records-count {
  display: flex;
  justify-content: flex-start;
  align-items: center;
}

.statistic__records-count-text {
  display: inline-block;
  margin-right: 10px;
  font-size: 16px;
  font-weight: 700;
  color: var(--text-color);
}

.statistic__records-count-num {
  display: inline-block;
  padding: 8px 15px;
  border-radius: 16px;
  background-color: var(--first-accent-color);
  font-size: 16px;
  font-weight: 500;
  color: #ffffff;
}

/*   Table  */
.statistic__table-wrap {
  margin-bottom: 25px;
}

.statistic__table-row {
  border: var(--border-line-style);
  border-radius: 8px;
  margin: 5px 0;
  padding: 10px 20px;
  transition: var(--transition-default);
  background-color: var(--content-block-bg);
}

.statistic__table-row_header {
  background-color: transparent;
}

.statistic__table-row:not(:first-child):hover {
  transform: translate(-1px, -1px);
  box-shadow: 0.5px 2px 6px 1px #dee2e6;
}

.statistic__table-row_header {
  border: none;
  font-size: 14px;
  line-height: 1.25;
  text-transform: uppercase;
  font-weight: 600;
  color: var(--text-color);
  text-align: center;
}

.statistic__table-header_name {
  text-align: left;
}

.statistic__table-col {
  display: flex;
  flex-direction: row;
  justify-content: center;
  align-items: center;
}

.statistic__table-col_id {
  font-size: 14px;
  font-weight: 600;
}

.statistic__table-col_name {
  flex-direction: column;
  align-items: flex-start;
}

.statistic__table-row-name {
  margin: 5px;
  font-size: 16px;
  font-weight: 600;
  line-height: 1.25;
}

.statistic__table-row-url {
  display: flex;
  flex-direction: row;
  justify-content: flex-start;
  align-items: center;
  font-size: 14px;
  font-weight: 400;
  color: var(--text-color);
}

.statistic__table-row-url-value {
  color: var(--first-accent-color);
  font-weight: 400;
  transition: var(--transition-default);
}

.statistic__table-row-url-value:hover {
  font-weight: 700;
  text-decoration: none;
}

.statistic__table-row-url-icon {
  margin-right: 5px;
}

.statistic__table-row-actions-icon:hover,
.statistic__table-row-socials-icon:hover {
  cursor: pointer;
}

.statistic__table-row-socials-wrap,
.statistic__table-row-actions-wrap {
  display: flex;
  flex-direction: row;
  justify-content: space-evenly;
  align-items: center;
  width: 100%;
  height: 100%;
}

.statistic__table-row-socials-icon {
  transition: var(--transition-default);
}

.statistic__table-row-socials-icon:hover {
  transform: scale(1.35);
}

/*  ==================  */

.statistics__btn-load-more {
  margin: 0 auto;
  display: block;
}

.statistic__search-invalid-error {
  font-size: 14px;
  color: darkred;
}
</style>