import axios from "axios";

const httpClient = (data, url, method) => {
  method = method || "post";
  return axios({
    method,
    url,
    data,
    headers: {
      "Content-Type": "application/json",
      "X-Requested-With": "XMLHttpRequest"
    }
  })
    .then(response => {
      if (typeof response.data !== "object") {
        return Promise.reject(response.data);
      }
      console.group(response.config.url);
      console.log(response.data.props);
      console.groupEnd();
      return response;
    })
    .then(response => {
      if (typeof response.data.props.notify !== "undefined") {
        Vue.notify(
          Object.assign(
            {
              group: "default-top-center",
              speed: 500,
              duration: 6000,
              type: "success"
            },
            response.data.props.notify
          )
        );
      }
      return response;
    })
    .catch(error => {
      console.group("Axios catch error");
      if (error.response !== undefined) {
        console.warn(error.response.data);
        let text = error.response.data.message;
        if (text == "CSRF token mismatch.") {
          alert("Сессия устарела, нужно авторизироваться снова");
          window.location = window.location.origin + "/lk/login";
        }
        if (error.response.data.errors) {
          text += "<br>";
          for (let i in error.response.data.errors) {
            text += error.response.data.errors[i].join(" ") + "<br>";
          }
        }
        Vue.notify({
          group: "default-top-center",
          type: "error",
          title: "Ошибка :/",
          text: text,
          duration: 6000,
          speed: 500
        });
      }
      console.groupEnd();
      throw error;
    });
};

export default httpClient;
