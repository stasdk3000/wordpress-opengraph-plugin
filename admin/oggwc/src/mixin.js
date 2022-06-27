import { sprintf } from './utils/i18n';
import messages from './utils/langs';

export default {
    methods: {

        __(text, domain) {
            console.log(domain);
            let localeCurrent = messages[window.oggwc_admin['locale']];
            return localeCurrent[text];
        },

        sprintf( fmt, ...args ) {
            return sprintf( fmt, ...args );
        },

        getFormData(data) {
            const formData = new FormData();

            for (let prop in data) {
                formData.append(prop, data[prop]);
            }

            return formData;

        },
    }
}