<style lang="scss" scoped>
.mainTitle {
  font-family: "Mulish-ExtraBold", serif;
  color: $baseStrongPurple !important;
  font-size: 45px;
}

.my-card:hover .bookInfos {
  background: rgb(70 56 83 / 88%);
}

.bookTitle {
  overflow: hidden;
  text-overflow: ellipsis;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  line-clamp: 2;
  -webkit-box-orient: vertical;
}

.authorsList {
  display: -webkit-box;
  -webkit-line-clamp: 1;
  -webkit-box-orient: vertical;
  overflow: hidden;
  text-overflow: ellipsis;
}

.bookInfos {
  background: rgb(95 87 105 / 88%);
  border-top: 1px solid white;
}

</style>

<template>
  <div class="d-flex flex-column align-items-center h-100">
    <div class="mainTitle mb-3 mt-4">Recherchez des livres</div>
    <q-input type="text" v-model="query" label="Recherche" filled color="purple-14"
             class="q-pa-md col-6" hint="Minimum de 2 lettres"
             :rules="[val => val && val.length > 0 || 'Veuillez rentrer un terme de recherche', val => val.length >= 2 || 'Minimum deux lettres pour débuter la recherche']"
             lazy-rules />

    <div class="d-flex w-100 justify-content-around flex-wrap mt-5">
      <template v-for="book in books" :key="book.id">
        <q-card class="my-card h-auto m-3 col-5 col-sm-4 col-md-3 col-xl-2">
          <a :href="book.volumeInfo.previewLink" target="_blank">
            <q-img :src="book.volumeInfo.imageLinks.thumbnail" width="259" style="height: 100%">
              <div class="absolute-bottom py-2 bookInfos">
                <div class="bookTitle" :title="book.volumeInfo.title">{{ book.volumeInfo.title }}</div>
                <div class="authorsList small">
                  <p class="m-0">
                    <template v-if="book.volumeInfo.authors">
                      <span :title="book.volumeInfo.authors.join(', ')">Par
                        <span v-for="(author, index) in book.volumeInfo.authors" :key="index">
                                  {{ author }}<span v-if="index + 1 < book.volumeInfo.authors.length">, </span>
                        </span>
                      </span>
                    </template>
                    <template v-else>
                      Par Inconnu
                    </template>
                  </p>
                </div>
                <div class="small">En
                  <template v-if="!book.volumeInfo.publishedDate">Non renseigné</template>
                  <template v-else>{{ formatPublishedDate(book.volumeInfo.publishedDate) }}</template>
                </div>
              </div>
            </q-img>
          </a>
        </q-card>
      </template>
    </div>

  </div>
</template>

<script>
import { defineComponent } from 'vue'
import axios from 'axios'
import { format } from 'date-fns'

export default defineComponent({
  name: 'HomePage',
  data () {
    return {
      query: null,
      books: null
    }
  },
  methods: {
    formatPublishedDate: function (date) {
      return format(new Date(date), 'yyyy')
    }
  },
  watch: {
    async query () {
      if (this.query.length >= 2) {
        this.books = await axios.get('https://www.googleapis.com/books/v1/volumes?q=' + this.query + '&maxResults=40')
          .then(function (response) {
            response.data.items.forEach((item) => {
              if (!Object.prototype.hasOwnProperty.call(item.volumeInfo, 'imageLinks')) {
                item.volumeInfo.imageLinks = {}
                item.volumeInfo.imageLinks.thumbnail = 'https://www.trentlakeslibrary.ca/en/resourcesGeneral/homepage/default-river.png'
              }
            })
            return response.data.items
          })
      }
    }
  }
})
</script>
