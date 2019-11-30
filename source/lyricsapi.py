from bs4 import BeautifulSoup
import requests
import json

def generate(artist,title):
    artist = artist.lower().replace(" ", "%20")
    title = title.lower().replace(" ", "%20")
    url = 'http://azlyrics.com/lyrics/'+artist+'/'+title +'.html'
    process(url, artist, title)

def process(url,artist,title):
    response = requests.get(url, timeout=10)
    content = BeautifulSoup(response.content, "html.parser")
    lyricsArr = []
    lyrics = content.find_all("div", attrs={"class": None, "id": None})
    lyrics = [x.getText() for x in lyrics]
    for x in lyrics:
        print(x, end="\n\n")
        lyricsObj = {
            "artist": artist,
            "title": title,
            "lyrics": x
        }
        lyricsArr.append(lyricsObj)
    with open('lyrics.json', 'w', encoding="utf-8-sig") as outfile:
        json.dump(lyricsArr, outfile, indent=4)
