function onJsonPosts(json) {
    const mainFeed = document.querySelector("#feed");

    if (!json) {
        console.log("EMPTY - No items fetched.");
        const notFound = document.createElement("div")
        notFound.classList.add("no-data")
        notFound.textContent = "There's nothing to be seen here. Create a post now!";
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

            mainFeed.appendChild(div);
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

fetch("fetch.php?posts=all").then(onResponse, onError).then(onJsonPosts);