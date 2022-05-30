'use strict';

document.addEventListener('DOMContentLoaded', () => {
    let list = document.getElementById('list');

    fetch('/api/redis', {
        method: 'GET'
    })
        .then(response => response.json())
        .then((json) => {
            let data = json.data;

            for (let key in data) {
                let el_li = document.createElement('li');

                el_li.innerHTML = key + ": " + data[key] + " <a href='#' class='link link--remove'>delete</a>";
                el_li.classList.add("item");
                el_li.setAttribute("data-name", key);
                el_li.addEventListener("click", async (ev) => {
                    let key = ev.target.closest(".item").dataset["name"];
                    console.log(key);
                    await fetch("/api/redis/" + key, {
                        method: "DELETE"
                    })
                        .then(response => response.json())
                        .then(json => {
                            console.log(json);
                            if (json.code === 200) {
                                ev.target.closest(".item").remove();
                            }
                        });
                });
                list.append(el_li);
            }
        });
});