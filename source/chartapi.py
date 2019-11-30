from bs4 import BeautifulSoup
import requests
import json

url = 'https://www.billboard.com/charts/hot-100'

response = requests.get(url, timeout=10)
content = BeautifulSoup(response.content, 'html.parser')
chart = content.select('div', attrs={"class": "chart-list container", "id": None})
rank = 1
for container in chart:
    song_title = container.find('span', {'class': 'chart-element__information__song'})
    song_artist = container.find('span', {'class': 'chart-element__information__artist'})
    print('\nPosition: #{}'.format(rank))
    print('Song: {}'.format((song_title).text))
    print('Artist: {}'.format((song_artist).text))
    rank += 1