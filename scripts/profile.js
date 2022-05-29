function deletePost(event) {
    // event.currentTarget.parentNode.parentNode.parentNode.parentNode. innerHTML = '';
    fetch("delete_post.php?q=" + event.currentTarget.dataset.postId);
    const toDelete = event.currentTarget.parentNode.parentNode;
    toDelete.remove();
}

function onJson(json) {
    const mainFeed = document.querySelector("#feed");

    if (!json) {
        console.log("EMPTY - No items fetched.");
        const notFound = document.createElement("div")
        notFound.classList.add("no-data")
        notFound.textContent = "You haven't made any post yet."
        mainFeed.appendChild(notFound);
    }

    if (json) {
        console.log(json);
        console.log("Fetched " + json.length + " items");
        for (let i = 0; i < json.length; i++) {
            const mainFeed = document.querySelector("#feed");

            const div = document.createElement("div");
            div.classList.add("post");

            const title = document.createElement("div");
            title.classList.add("title");
            title.textContent = json[i].content.title;
            div.appendChild(title);

            const author = document.createElement("div");
            author.classList.add("author");
            author.textContent = "@" + json[i].author;
            div.appendChild(author);

            const content = document.createElement("div");
            content.classList.add("content");
            content.textContent = json[i].content.caption;
            div.appendChild(content);

            const gif = document.createElement("img");
            gif.classList.add("gif");
            gif.src = json[i].content.gif;
            div.appendChild(gif);

            const pageDeleteContent = document.createElement('div');
            pageDeleteContent.classList.add('deletecontent');
            div.appendChild(pageDeleteContent);

            const pageDelete = document.createElement('button');
            pageDelete.classList.add('delete');
            pageDelete.textContent = "Delete post";
            pageDelete.dataset.postId = json[i].id_post;
            pageDeleteContent.appendChild(pageDelete);
            mainFeed.appendChild(div);



            const deleteButtons = document.querySelectorAll('.delete');
            for (const deleteButton of deleteButtons) {
                deleteButton.addEventListener('click', deletePost);
            }
        }

        const feedEnd = document.createElement('div');
        feedEnd.classList.add("end");
        // feedBegin.classList.add('textStart');
        feedEnd.textContent = "You've reached the end. " + json.length + " posts shown.";
        mainFeed.appendChild(feedEnd);
    }
}

function onResponse(response) {
    return response.json();
}

function onError(error) {
    console.log("Error while fetching posts: " + error);
}

fetch("fetch.php?posts=mine").then(onResponse).then(onJson);