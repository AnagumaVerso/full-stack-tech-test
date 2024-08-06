<script>
export default {
  name: 'BookListing',   
  data() {
    return {
      books: [],
      searchQuery: '',
      filteredBooks: []
    };
  },
  created() {
    this.fetchBooks();
  },
  methods: {
    fetchBooks() {
      axios.get('/api/books')
        .then(response => {
          this.books = response.data.data;
          this.filteredBooks = this.books;
        })
        .catch(error => {
          console.error(error);
        });
    },
    filterBooks() {
      this.filteredBooks = this.books.filter(book =>
        book.title.toLowerCase().includes(this.searchQuery.toLowerCase())
      );
    },
    editBook(book) {
      // Handle edit book logic here
    },
    deleteBook(bookId) {
      axios.delete(`/api/books/${bookId}`)
        .then(response => {
          this.fetchBooks();
        })
        .catch(error => {
          console.error(error);
        });
    }
  }
};
</script>

<template>
  <div>
    <div class="bg-gray-800 pt-8 pb-20">
      <div class="w-9/12 text-center mr-auto ml-auto -mt-0 mb-0">
        <h1 class="text-orange text-5xl p-10">Book Shop</h1>
        <p class="w-9/12 mr-auto ml-auto -mt-0 mb-0 text-white">
          Cupcake ipsum dolor sit amet croissant. I love topping candy canes sweet roll croissant caramels. Soufflé macaroon liquorice chocolate tart I love.
        </p>
        <input type="text" placeholder="Search by book title ..." v-model="searchQuery" @input="filterBooks" class="rounded-md border-gray-400 border-solid border-[1px] p-2 w-96 mt-10">
      </div>
    </div>
    <div class="w-9/12 mr-auto ml-auto -mt-0 mb-0">
      <table class="w-full border-collapse mt-10">
        <thead>
          <tr>
            <th class="border-1 border-gray-600 p-4 bg-gray-700 text-white">Title</th>
            <th class="border-1 border-gray-600 p-4 bg-gray-700 text-white">Author</th>
            <th class="border-1 border-gray-600 p-4 bg-gray-700 text-white">Rating</th>
            <th class="border-1 border-gray-600 p-4 bg-gray-700 text-white">Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="book in filteredBooks" :key="book.id" class="border-1 border-gray-600">
            <td class="border-1 border-gray-600 p-4">{{ book.title }}</td>
            <td class="border-1 border-gray-600 p-4">{{ book.author }}</td>
            <td class="border-1 border-gray-600 p-4">{{ book.rating }}</td>
            <td class="border-1 border-gray-600 p-4">
              <div class="flex">
                <a href="#" @click.prevent="editBook(book)" class="text-blue-500">Edit</a>
                <a href="#" @click.prevent="deleteBook(book.id)" class="text-red-500 ml-4">Delete</a>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
