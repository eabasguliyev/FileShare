$(() => {
  const langsEl = $("#langs");

  langsEl.on("change", function () {
    const lang = this.value.toLowerCase();

    const langs = ["az", "en"];

    const arr = location.pathname.split("/");

    if (langs.includes(arr[2])) {
      arr[2] = lang;
    } else {
      arr.splice(2, 0, lang);
    }

    location.pathname = arr.join("/");
  });
});
