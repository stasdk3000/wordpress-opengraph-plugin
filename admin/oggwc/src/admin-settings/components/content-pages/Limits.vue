<template>
  <div class="limits">
    <div class="content-block-item">
      <div class="content-block-item__title content-block-item__title_min">
        {{ __('Limits', 'oggwc') }}
      </div>

      <!--    License key    -->
      <div v-show="false" class="limits__license-key mb-3">
        <label for="license-key">{{ __('Licence_Token', 'oggwc') }}:</label>
        <b-form-input
            id="license-key"
            class="custom__input custom__input_licence-key"
            v-model="inputLicenseKey"
            readonly
            placeholder="Enter your license key"
        ></b-form-input>
      </div>

      <!--    License status    -->
      <div class="limits__license-status mb-3">
        <span>
          {{ __('Licence_Status', 'oggwc') }}:
          <b-badge variant="success"
                   v-if="licenseStatus === 'Activated'"
          >
            {{ licenseStatus }}
          </b-badge>
          <b-badge variant="danger"
                   v-if="licenseStatus === 'Not activated'"
          >
            {{ licenseStatus }}
          </b-badge>
        </span>
      </div>

      <b-button class="button__custom button__custom_blue"
                @click="settingsSave"
      >{{ __('Save', 'oggwc') }}
      </b-button>

      <!--    License status    -->
      <div v-show="false" class="limits__post-limits mt-5">
        <div>{{ __('Count_requests', 'oggwc') }}</div>

        <!--        <b-progress :value="currentPostLimits" :max="maxPostLimits" show-value></b-progress>-->
        <div class="progress-bar__wrap">
          <div class="progress-bar__inner">
            <svg class="progress-bar__svg">
              <circle class="progress-bar__circle-outer" cx="70" cy="70" r="70"></circle>
              <circle class="progress-bar__circle-inner" cx="70" cy="70" r="70"
                      :style="{'stroke-dashoffset': 'calc(440 - (440 *' + currentPostLimits + ') / ' + maxPostLimits + ')'}"
              ></circle>
            </svg>
            <div class="progress-bar__info">
              <div class="progress-bar__inner-text">{{ currentPostLimits }}
                <div class="progress-bar__inner-text-small">
                  {{ __('out_of', 'oggwc') }} {{ maxPostLimits }}
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="limits__input-wrap">
          <label for="limits__posts-count-input">{{ __('Your_Limits', 'oggwc') }}: </label>
          <b-form-input id="limits__posts-count-input" v-model="inputPostsCount" type="number"
                        class="custom__input"
                        :value="currentPostLimits"
                        :min="currentPostLimits" :max="maxPostLimits"
                        @change="checkPostLimitCount(inputPostsCount)"
          ></b-form-input>
        </div>

        <b-button class="button__custom button__custom_blue mt-3"
                  v-if="inputPostsCount > currentPostLimits && inputPostsCount <= maxPostLimits"
        >{{ __('Update_Limits', 'oggwc') }}
        </b-button>
      </div>

    </div>
  </div>
</template>

<script>
import Mixin from '../../../mixin'
import axios from "axios";

export default {
  name: "Limits",
  mixins: [Mixin],
  data: () => ({
    inputPostsCount: 0,
    inputLicenseKey: '',
    licenseStatus: '-',
    currentPostLimits: 0,
    maxPostLimits: 0,
    oavar: []
  }),

  methods: {

    checkPostLimitCount(counter) {
      if (counter < this.currentPostLimits || counter > this.maxPostLimits)
        this.inputPostsCount = this.currentPostLimits
    },

    /**
     * Save all settings
     */
    settingsSave() {
      this.$store.state.loaderIsShow = true

      let data = {
            'action': 'oggwc_update_admin_settings',
            'oggwc_lic_key': this.inputLicenseKey,
          },
          that = this,
          params = this.getFormData(data)

      axios
          .post(that.oavar.ajax_url, params)
          .then(response => {
            //TODO ALERT
            console.log(response)
            this.$store.state.loaderIsShow = false
          })
          .catch(error => {
            console.log(error)
            this.$store.state.loaderIsShow = false
          })
    },

    /**
     * Update limits info
     */
    getLimits() {
      this.$store.state.loaderIsShow = true

      /*let data = {
            'token': this.inputLicenseKey,
          },
          that = this,
          params = this.getFormData(data)*/

      axios
          .get(this.oavar.api_endpoints.get_limits, {
            headers: {
              token: this.inputLicenseKey
            }
          })
          .then(response => {
            //TODO ALERT
            let responseData = response.data ? response.data : null
            console.log(responseData)

            if ( responseData ) {
              this.currentPostLimits = +responseData.req
              this.maxPostLimits = +responseData.limit
              this.inputPostsCount = this.currentPostLimits
            }

            this.$store.state.loaderIsShow = false
          })
          .catch(error => {
            console.log(error)
            this.$store.state.loaderIsShow = false
          })
    },
  },


  created() {
    // init global js WP var
    this.oavar = window.oggwc_admin;
    this.inputLicenseKey = this.oavar.oggwc_lic_key;
    this.getLimits()
  },

  /*  MOUNTED  */
  mounted() {
    this.inputPostsCount = this.currentPostLimits

    // check licence from global var
    //this.oavar.oggwc_lic_key ? this.licenseStatus = 'Activated' : this.licenseStatus = 'Not activated'
    this.licenseStatus = 'Activated';
  },
}
</script>

<style scoped>
.progress-bar__wrap {
  position: relative;
  width: 155px;
  height: 200px;
  display: flex;
  justify-content: center;
  align-items: center;
  flex-direction: column;
  margin-bottom: 15px;
}

.limits__input-wrap {
  display: flex;
  flex-direction: row;
  justify-content: flex-start;
  align-items: center;
}

.limits__input-wrap label {
  margin-right: 20px;
  margin-bottom: 0;
}

#limits__posts-count-input {
  max-width: 100px;
}

.progress-bar__inner, .progress-bar__svg {
  position: relative;
  width: 150px;
  height: 150px;
}

circle {
  width: 150px;
  height: 150px;
  fill: none;
  stroke-width: 10;
  stroke: #000;
  transform: translate(5px, 5px);
  stroke-dasharray: 440;
  stroke-dashoffset: 440;
}

.progress-bar__info {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
  color: #111;
}

.progress-bar__inner-text {
  font-size: 40px;
  line-height: 1.15;
  font-weight: 900;
  display: flex;
  flex-direction: column;
  width: 100%;
  height: 100%;
  justify-content: center;
  align-items: center;
  color: var(--content-block-title);
}

.progress-bar__inner-text-small {
  font-size: 12px;
  font-weight: 400;
  color: var(--text-color);
  text-align: center;
}

.progress-bar__circle-outer {
  stroke-dashoffset: 0;
  stroke: #f3f3f3;
}

.progress-bar__circle-inner {
  stroke-dashoffset: 0;
  stroke: #03a9f4;
  stroke-width: 8px;
}
</style>