function onResponse(response) {
    if (!response.ok) {return null};
    return response.json();
}

function onLoadEvents(json){
    console.log(json)
    const eventsContainer = document.querySelector('#events-container')
    for (let i = 0; i < json.length; i++){
        const flexItem = document.createElement('div')
        flexItem.classList.add('flex-item')
        const imgContainer = document.createElement('div')
        imgContainer.classList.add('img-container')
        const img = document.createElement('img')
        img.src = json[i].cover
        imgContainer.appendChild(img)

        const info = document.createElement('div')
        info.classList.add('info')
        const h1 = document.createElement('h1')
        h1.textContent = json[i].title
        const p = document.createElement('p')
        p.textContent = json[i].date_and_time.split(" ")[0]
        info.appendChild(h1)
        info.appendChild(p)
        flexItem.appendChild(imgContainer)
        flexItem.appendChild(info)
        eventsContainer.appendChild(flexItem)
    }
}

fetch("load_events.php?is_checked=false&limit=4").then(onResponse).then(onLoadEvents)