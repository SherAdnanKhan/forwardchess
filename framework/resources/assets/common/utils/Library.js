import moment from 'moment';

export function isEmail(value) {
    const validator = require('email-validator');

    return validator.validate(value);
}

export function isInteger(str) {
    const pattern = /^\d+$/;
    return pattern.test(str);
}

export function isUrl(url) {
    return require('is-url')(url);
}

export function isEmpty(data) {
    if ((typeof (data) === 'number') || (typeof (data) === 'boolean')) {
        return false;
    }

    if ((typeof (data) === 'undefined') || (data === null)) {
        return true;
    }

    if (typeof (data.length) !== 'undefined') {
        return data.length === 0;
    }

    let count = 0;
    for (let i in data) {
        if (data.hasOwnProperty(i)) {
            count++;
        }
    }

    return (count === 0);
}

export function guid() {
    function s4() {
        return Math.floor((1 + Math.random()) * 0x10000)
            .toString(16)
            .substring(1);
    }

    return s4() + s4() + '-' + s4() + '-' + s4() + '-' + s4() + '-' + s4() + s4() + s4();
}

export function ucWords(str) {
    str = str.toLowerCase();
    return str.replace(/(^([a-zA-Z\p{M}]))|([ -][a-zA-Z\p{M}])/g,
        function ($1) {
            return $1.toUpperCase();
        });
}

export function loadImages(images) {
    const promises = [];
    for (let i = 0; i < images.length; i++) {
        promises.push(new Promise(resolve => {
            const bgImage = new Image();
            bgImage.src = images[i];
            bgImage.onload = () => {
                resolve(images[i]);
            };
        }));
    }

    return Promise.all(promises);
}

/**
 * @param {string} str
 * @param {Array} params
 * @returns {string}
 */
export function sprintf(str, params = []) {
    if (arguments.length) {
        for (let key in params) {
            str = str.replace(new RegExp('\\{' + key + '\\}', 'gi'), params[key]);
        }
    }

    return str;
}

export function getQueryParams(queryString) {
    // remove any preceding url and split
    queryString = queryString.substring(queryString.indexOf('?') + 1).split('&');
    const params = {}, d = decodeURIComponent;
    let pair;

    for (let i = queryString.length - 1; i >= 0; i--) {
        pair = queryString[i].split('=');
        params[d(pair[0])] = d(pair[1] || '');
    }

    return params;
}

export function b64EncodeUnicode(str) {
    // first we use encodeURIComponent to get percent-encoded UTF-8,
    // then we convert the percent encodings into raw bytes which
    // can be fed into btoa.
    return btoa(encodeURIComponent(str).replace(/%([0-9A-F]{2})/g,
        function toSolidBytes(match, p1) {
            return String.fromCharCode('0x' + p1);
        }));
}

export function b64DecodeUnicode(str) {
    // Going backwards: from bytestream, to percent-encoding, to original string.
    return decodeURIComponent(atob(str).split('').map(function (c) {
        return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
    }).join(''));
}

export function getRandomInt(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
}

export function formatIntervalForPicker(startDate, endDate) {
    return [moment(startDate).valueOf(), moment(endDate).valueOf()];
}

export function formatIntervalForApi(period) {
    return {
        startDate: moment(period[0]).format('YYYY-MM-DD'),
        endDate  : moment(period[1]).format('YYYY-MM-DD')
    };
}
