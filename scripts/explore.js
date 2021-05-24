function onResponse(response) {
    if (!response.ok) {return null};
    return response.json();
}

function fetchProfileInfo(){

    function fetchProfileInfoJson(json){
    userId = json.id
    const infoBlock = document.querySelector('#profile .info-block')

    const imgContainer = infoBlock.querySelector('.img-container')
    imgContainer.innerHTML = ""
    const img = document.createElement("img")
    img.setAttribute("src", json.avatar)
    imgContainer.appendChild(img)

    const names = infoBlock.querySelector('.names')
    names.innerHTML = ""
    const h1 = document.createElement("h1")
    h1.textContent = json.name + ' ' + json.surname
    const p = document.createElement("p")
    p.textContent = json.username
    names.appendChild(h1)
    names.appendChild(p)

    const nFollowingDiv = infoBlock.querySelector('.activity').querySelector('.nfollowing')
    nFollowingDiv.innerHTML = ""
    const nFollowing = document.createElement('p')
    nFollowing.classList.add('number')
    nFollowing.textContent = json.nfollowing
    const captionFollowing = document.createElement('p')
    captionFollowing.textContent = 'Direttori Artistici seguiti '
    nFollowingDiv.appendChild(nFollowing)
    nFollowingDiv.appendChild(captionFollowing)

    const nReviewsDiv = infoBlock.querySelector('.activity').querySelector('.nreviews')
    nReviewsDiv.innerHTML = ""
    const nReviews = document.createElement('p')
    nReviews.classList.add('number')
    nReviews.textContent = json.nreviews
    const captionReviews = document.createElement('p')
    captionReviews.textContent = 'Recensioni'
    nReviewsDiv.appendChild(nReviews)
    nReviewsDiv.appendChild(captionReviews)

}
    
    fetch("fetch_profile_info.php").then(onResponse).then(fetchProfileInfoJson);
}

function chooseArtwork(event) {
    function quitModalExit(event){
        modalWindow.remove()
        document.body.classList.remove('no-scroll');
    }

    function searchArtworks(event){

        function reset(event){
            const toAddButton = event.currentTarget.parentNode.querySelector('input[type="button"')
            toAddButton.classList.remove('hidden')
            event.currentTarget.remove()
            document.querySelector('form[name="write-review"] .choose .container').remove()
        }

        function addToPostWindow(event){
            const imageAddress = event.currentTarget.querySelector('img').currentSrc

            const choose = document.querySelector('#create-post form .choose')
            const button = choose.querySelector('input[type="button"]')
            button.classList.add('hidden')

            const container = document.createElement('div')
            container.dataset.id = event.currentTarget.dataset.id
            container.classList.add('container')

            const imageContainer = document.createElement('div')
            const image = document.createElement('img')
            image.src = imageAddress
            imageContainer.appendChild(image)

            const infoContainer = document.createElement('div')
            const h1 = document.createElement('h1')
            h1.textContent = event.currentTarget.querySelector('h1').textContent
            infoContainer.appendChild(h1)
                      
            const imgExit = document.createElement('img')
            imgExit.classList.add('quit')
            imgExit.setAttribute('src', 'images/x-circle.svg')
            imgExit.addEventListener('click', reset)
            
            choose.appendChild(imgExit)
            container.appendChild(imageContainer)
            container.appendChild(infoContainer)
            choose.appendChild(container)
        }

        function onJson(json) {
            artworks.innerHTML = ""
            for (let i = 0; i < json.length; i++) {
                //artworks è il contenitore
                
                const div = document.createElement('div')
                div.classList.add('artwork')
                div.dataset.id = json[i].id

                const overlay = document.createElement('div')
                overlay.classList.add('overlay')

                const caption = document.createElement('h1')
                caption.textContent = json[i].title
                const img = document.createElement('img')
                img.setAttribute('src', json[i].image_id)

                div.appendChild(overlay)
                div.appendChild(img)
                div.appendChild(caption)

                div.addEventListener('click', addToPostWindow)
                div.addEventListener('click', quitModalExit)
                artworks.appendChild(div)
            }

        }

        function onResponse(response) {
            return response.json()
        }

        fetch('fetch_artworks.php?q=' + inputSearch.value).then(onResponse).then(onJson)
    }
    
    document.body.classList.add('no-scroll');

    const modalWindow = document.createElement('div')
    modalWindow.classList.add('modal-window')
    modalWindow.style.top = window.pageYOffset + 'px';

    //div search
    const search = document.createElement('div')
    search.classList.add('search')
    const imgExit = document.createElement('img')
    imgExit.setAttribute('src', 'images/x-circle.svg')
    imgExit.addEventListener('click', quitModalExit)
    search.appendChild(imgExit)

    const label = document.createElement('label') //titolo cerca
    label.textContent = 'Quale opera vuoi valutare?'
    label.setAttribute('for', 'search')
    search.appendChild(label)

    const inputSearch = document.createElement('input') //creazione input
    inputSearch.setAttribute('type', 'text')
    const buttonSearch = document.createElement('button') //creazione button
    buttonSearch.setAttribute('type', 'button')
    buttonSearch.textContent = "Cerca"
    buttonSearch.addEventListener('click', searchArtworks)
    search.appendChild(inputSearch)
    search.appendChild(buttonSearch)

    //div artworks
    const artworks = document.createElement('div')
    artworks.classList.add('artworks')

    modalWindow.appendChild(search)
    modalWindow.appendChild(artworks)
    document.body.appendChild(modalWindow)
}

function post(event){
    event.preventDefault()
    if(
        formReview.querySelector('.choose div.container') === null || 
        formReview.querySelector('.write textarea').textLength < 10 || 
        formReview.querySelector('select[name="rating"] option[value="0"]').selected
    ){
        if(!formReview.querySelector('p.error')){
            const error = document.createElement('p')
            error.classList.add('error')
            error.textContent = "Scegliere un'opera. La lunghezza del testo deve essere maggiore di 10 caratteri e infine attribuire una valutazione."
            formReview.insertBefore(error, formReview.firstChild)
        }
    }else{
        formData = new FormData()
        formData.append("artwork_id", formReview.querySelector('.choose .container').dataset.id)
        formData.append("img", formReview.querySelector('.choose .container img').src)
        formData.append("stars", formReview.querySelector('.submit .rating select').value)
        formData.append("comment", formReview.querySelector('.write textarea').value)

        fetch("post_review.php", {method: 'post', body: formData}).then(onResponse).then(postWarning).then(loadProfileReviews)
        resetForm()
    }
}

function postWarning(json){
    if(!json.ok){
        const reviews = document.querySelector('#reviews')
        const warning = document.createElement('p')
        warning.classList.add('warning')
        warning.textContent = "Operazione non autorizzata. Hai già inserito questa recensione"
        reviews.insertBefore(warning, reviews.firstChild)
        setTimeout(warning.remove(), 5000)
    }
}

function resetForm(){
    formReview.querySelector('.choose img.quit').remove()
    formReview.querySelector('.choose div.container').remove()
    const button = formReview.querySelector('.choose input')
    button.classList.remove('hidden')
    formReview.querySelector('.write textarea').value = ""
    formReview.querySelector('select[name="rating"] option[value="0"]').selected = 'selected'
}

function convertStars(number){
    switch(number){
        case '1': return "&#9733&#9734&#9734&#9734&#9734"; break;
        case '2': return "&#9733&#9733&#9734&#9734&#9734"; break;
        case '3': return "&#9733&#9733&#9733&#9734&#9734"; break;
        case '4': return "&#9733&#9733&#9733&#9733&#9734"; break;
        case '5': return "&#9733&#9733&#9733&#9733&#9733"; break;
        default: return "";
    }
}

function onLoadOthersReviewsJson(json){
    const others = document.querySelector('#reviews')

    const articleList = others.querySelectorAll('article')
    for(let item of articleList) item.remove();

    for (let i = 0; i < json.length; i++){
        const article = document.createElement('article')

        const topArticle = document.createElement('div')
        topArticle.classList.add('top-article')
        const category = document.createElement('span')
        category.textContent = json[i].category
        const stars = document.createElement('span')
        stars.classList.add('stars')
        stars.innerHTML = convertStars(json[i].stars)
        topArticle.appendChild(category)
        topArticle.appendChild(stars)

        const userInfo = document.createElement('div')
        userInfo.classList.add('user-info')

        const avatar = document.createElement('div')
        avatar.classList.add('avatar')
        const avatarImg = document.createElement('img')
        avatarImg.src = json[i].avatar
        avatar.appendChild(avatarImg)
        userInfo.appendChild(avatar)

        const userDetails = document.createElement('div')
        userDetails.classList.add('user-details')

        const name = document.createElement('p')
        name.textContent = json[i].name +' ' + json[i].surname
        userDetails.appendChild(name)
        const username = document.createElement('p')
        username.classList.add('username')
        username.textContent = json[i].username
        userDetails.appendChild(username)
        userInfo.appendChild(userDetails)

        const artwork = document.createElement('div')
        artwork.classList.add('artwork')

        const title = document.createElement('p')
        title.textContent = json[i].title
        artwork.appendChild(title)
        const placeOfOrigin = document.createElement('p')
        placeOfOrigin.classList.add('place-of-origin')
        placeOfOrigin.textContent = json[i].place_of_origin + ', ' + json[i].publication_year
        artwork.appendChild(placeOfOrigin)
        const artists = document.createElement('p')
        artists.textContent = json[i].artists 
        artwork.appendChild(artists)
        const imgContainer = document.createElement('div')
        const img = document.createElement('img')
        img.src = json[i].img
        imgContainer.appendChild(img)
        artwork.appendChild(imgContainer)
        const comment = document.createElement('p')
        comment.classList.add('comment')
        comment.textContent = json[i].body

        const bottomArticle = document.createElement('div')

        bottomArticle.classList.add('bottom-article')
        const publication = document.createElement('span')
        publication.textContent = json[i].publication_date
        bottomArticle.appendChild(publication)
        const like = document.createElement('span')
        like.classList.add('like')
        like.dataset.userReview = json[i].user_review
        like.dataset.artworkReview = json[i].artwork_review
        const numLikes = document.createElement('span')
        numLikes.classList.add('num-likes')
        numLikes.textContent = json[i].likes
        const imgLike = document.createElement('img')
        if(json[i].is_liked){
            imgLike.src = "images/like.png"
            like.addEventListener('click', unlikeReview)
        }else{
            imgLike.src = "images/unlike.png"
            like.addEventListener('click', likeReview)
        }
        like.appendChild(numLikes)
        like.appendChild(imgLike)
        bottomArticle.appendChild(like)

        article.appendChild(topArticle)
        article.appendChild(artwork)
        article.appendChild(userInfo)
        article.appendChild(comment)
        article.appendChild(bottomArticle)

        others.appendChild(article)
    }
}

function onLoadProfileReviewsJson(json){
    const profile = document.querySelector('#profile')
    const articleList = profile.querySelectorAll('article')
    for(let item of articleList) item.remove();
    fetchProfileInfo()

    for (let i = 0; i < json.length; i++){
        const article = document.createElement('article')

        const topArticle = document.createElement('div')
        topArticle.classList.add('top-article')
        const category = document.createElement('span')
        category.textContent = json[i].category
        const stars = document.createElement('span')
        stars.classList.add('stars')
        stars.innerHTML = convertStars(json[i].stars)
        topArticle.appendChild(category)
        topArticle.appendChild(stars)

        const userInfo = document.createElement('div')
        userInfo.classList.add('user-info')

        const avatar = document.createElement('div')
        avatar.classList.add('avatar')
        const avatarImg = document.createElement('img')
        avatarImg.src = json[i].avatar
        avatar.appendChild(avatarImg)
        userInfo.appendChild(avatar)

        const userDetails = document.createElement('div')
        userDetails.classList.add('user-details')

        const name = document.createElement('p')
        name.textContent = json[i].name +' ' + json[i].surname
        userDetails.appendChild(name)
        const username = document.createElement('p')
        username.classList.add('username')
        username.textContent = json[i].username
        userDetails.appendChild(username)
        userInfo.appendChild(userDetails)

        const artwork = document.createElement('div')
        artwork.classList.add('artwork')

        const title = document.createElement('p')
        title.textContent = json[i].title
        artwork.appendChild(title)
        const placeOfOrigin = document.createElement('p')
        placeOfOrigin.classList.add('place-of-origin')
        placeOfOrigin.textContent = json[i].place_of_origin + ', ' + json[i].publication_year
        artwork.appendChild(placeOfOrigin)
        const artists = document.createElement('p')
        artists.textContent = json[i].artists 
        artwork.appendChild(artists)
        const imgContainer = document.createElement('div')
        const img = document.createElement('img')
        img.src = json[i].img
        imgContainer.appendChild(img)
        artwork.appendChild(imgContainer)
        const comment = document.createElement('p')
        comment.classList.add('comment')
        comment.textContent = json[i].body

        const bottomArticle = document.createElement('div')

        bottomArticle.classList.add('bottom-article')
        const publication = document.createElement('span')
        publication.textContent = json[i].publication_date
        bottomArticle.appendChild(publication)
        const like = document.createElement('span')
        like.classList.add('like')
        like.dataset.userReview = json[i].user_review
        like.dataset.artworkReview = json[i].artwork_review
        const numLikes = document.createElement('span')
        numLikes.classList.add('num-likes')
        numLikes.textContent = json[i].likes
        const imgLike = document.createElement('img')
        if(json[i].is_liked){
            imgLike.src = "images/like.png"
            like.addEventListener('click', unlikeReview)
        }else{
            imgLike.src = "images/unlike.png"
            like.addEventListener('click', likeReview)
        }
        like.appendChild(numLikes)
        like.appendChild(imgLike)
        bottomArticle.appendChild(like)

        article.appendChild(topArticle)
        article.appendChild(artwork)
        article.appendChild(userInfo)
        article.appendChild(comment)
        article.appendChild(bottomArticle)

        profile.appendChild(article)
    }
}

function loadProfileReviews(){
    fetch("load_reviews.php?user=true&limit=10").then(onResponse).then(onLoadProfileReviewsJson)
}

function loadOthersReviews(){
    fetch("load_reviews.php?&limit=10").then(onResponse).then(onLoadOthersReviewsJson)
}

function unlikeReview(event){
    
    event.currentTarget.removeEventListener('click', unlikeReview)
    const numLikes = event.currentTarget.querySelector('.num-likes')

    function onUnlikeJson(json){
        numLikes.textContent = json.n_likes
    }

    const img = event.currentTarget.querySelector('img')
    img.src = "images/unlike.png"

    const user_review = event.currentTarget.dataset.userReview
    const artwork_review = event.currentTarget.dataset.artworkReview
    fetch("unlike.php?user_review=" + user_review + "&artwork_review=" + artwork_review).then(onResponse).then(onUnlikeJson)

    event.currentTarget.addEventListener('click', likeReview)
}

function likeReview(event){
    event.currentTarget.removeEventListener('click', likeReview)
    const numLikes = event.currentTarget.querySelector('.num-likes')

    function onLikeJson(json){
        numLikes.textContent = json.n_likes
    }

    const img = event.currentTarget.querySelector('img')
    img.src = "images/like.png"

    const user_review = event.currentTarget.dataset.userReview
    const artwork_review = event.currentTarget.dataset.artworkReview
    fetch("like.php?user_review=" + user_review + "&artwork_review=" + artwork_review).then(onResponse).then(onLikeJson)
    event.currentTarget.addEventListener('click', unlikeReview)
}

function loadFollowings(event){

    function onLoadFollowingsJson(json){
        console.log(json)
        const userSubscription = document.querySelector('#subscriptions .user-subscriptions')
        const otherDirectors = document.querySelector('#subscriptions .other-directors')
        userSubscription.innerHTML = ""
        otherDirectors.innerHTML = ""
        for (let i = 0; i < json.length; i++){

            const directorDiv = document.createElement('div')
            directorDiv.classList.add('director')
            directorDiv.dataset.id = json[i].cf

            const imgContainer = document.createElement('span')
            imgContainer.classList.add('img-container')
            const profileImage = document.createElement('img')
            profileImage.src = json[i].img
            imgContainer.appendChild(profileImage)

            const infoContainer = document.createElement('span')
            infoContainer.classList.add('info-container')
            const name = document.createElement('p')
            name.textContent = json[i].name + " " + json[i].surname
            const qualification = document.createElement('p')
            qualification.classList.add('qualification')
            qualification.textContent = json[i].qualification
            infoContainer.appendChild(name)
            infoContainer.appendChild(qualification)

            directorDiv.appendChild(imgContainer)
            directorDiv.appendChild(infoContainer)

            const followContainer = document.createElement('span')
            followContainer.classList.add('follow-container')
            const followImage = document.createElement('img')

            if(json[i].is_following == true){
                followImage.src = "images/person-check.svg"
                followImage.addEventListener('click', unfollowDirector)
                followContainer.appendChild(followImage)
                directorDiv.appendChild(followContainer)
                userSubscription.appendChild(directorDiv)
            }else{
                followImage.src = "images/person-plus.svg"
                followImage.addEventListener('click', followDirector)
                followContainer.appendChild(followImage)
                directorDiv.appendChild(followContainer)
                otherDirectors.appendChild(directorDiv)
            }
        }
        //alla fine del for
        const firstH4 = document.querySelector('#subscriptions h4:first-child')
        const secondH4 = document.querySelectorAll('#subscriptions h4')[1]

        if(!otherDirectors.querySelector('.director')){
            const nothing = document.createElement('p')
            nothing.textContent = "Non c'è più altro da mostrare"
            otherDirectors.appendChild(nothing)
        }else secondH4.textContent = "Altri Direttori Artistici"

        if(!userSubscription.querySelector('.director')){
            firstH4.classList.add('hidden')
            secondH4.textContent = "Tutti i Direttori Artistici"
        }else firstH4.classList.remove('hidden')
    }
    fetch("load_followings.php?").then(onResponse).then(onLoadFollowingsJson)
}

function unfollowDirector(event){
    function onFollowJson(json){
        console.log(json)
        if(json.ok == true){
            loadFollowings()
            fetchProfileInfo();
        }else alert('Qualcosa è andato storto')
    }
    const idDirector = event.currentTarget.parentNode.parentNode.dataset.id
    fetch("follow_dir.php?request=unfollow&id=" + idDirector).then(onResponse).then(onFollowJson)
}

function followDirector(event){
    function onFollowJson(json){
        console.log(json)
        if(json.ok == true){
            loadFollowings()
            fetchProfileInfo();
        }else alert('Qualcosa è andato storto')
    }
    const idDirector = event.currentTarget.parentNode.parentNode.dataset.id
    fetch("follow_dir.php?request=follow&id=" + idDirector).then(onResponse).then(onFollowJson)
}

let limit = 10
let userId = 0; //viene settato all'ingresso
fetchProfileInfo();
const formReview = document.querySelector('form[name="write-review"]')
const buttonArtwork = formReview.querySelector('.choose input[type="button"]')
buttonArtwork.addEventListener('click', chooseArtwork)

const submitPost = formReview.querySelector('.submit input[type="submit"]')
submitPost.disabled = false
submitPost.addEventListener('click', post)
loadProfileReviews()
loadOthersReviews()
loadFollowings()