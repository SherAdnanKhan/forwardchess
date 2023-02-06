import axios from 'axios';
import qs from 'qs';

export function getResponse(response) {
  if (response.data) {
    return response.data.hasOwnProperty('data') ? response.data.data : response.data;
  } else {
    return response;
  }
}

export default function HttpClient(baseURL = null, dataInterceptor = true) {
  baseURL = baseURL || `${window.apiBaseURL}`;

  const instance = axios.create({
    baseURL,
    responseType    : 'json',
    paramsSerializer: function (params) {
      return qs.stringify(params);
    }
  });

  instance.interceptors.response.use(function (response) {
    return dataInterceptor ? getResponse(response) : response;
  }, function (error) {
    if (error.response) {
      return Promise.reject(getResponse(error.response));
    }

    return Promise.reject(error);
  });

  return instance;
}
