# trainingphp

#Login
using System;
using System.Linq;
using Microsoft.EntityFrameworkCore;
using BCrypt.Net;

public class User
{
    public int UserId { get; set; }
    public string Username { get; set; }
    public string PasswordHash { get; set; }
    public string Email { get; set; }
}

public class AppDbContext : DbContext
{
    public DbSet<User> Users { get; set; }

    protected override void OnConfiguring(DbContextOptionsBuilder optionsBuilder)
    {
        optionsBuilder.UseSqlServer(@"Server=.\SQLEXPRESS;Database=UserDb;Trusted_Connection=True;");
    }
}

public class UserService
{
    public void RegisterUser(string username, string password, string email)
    {
        using (var context = new AppDbContext())
        {
            var passwordHash = BCrypt.Net.BCrypt.HashPassword(password);
            var user = new User
            {
                Username = username,
                PasswordHash = passwordHash,
                Email = email
            };
            context.Users.Add(user);
            context.SaveChanges();
        }
    }

    public bool LoginUser(string username, string password)
    {
        using (var context = new AppDbContext())
        {
            var user = context.Users.SingleOrDefault(u => u.Username == username);
            if (user != null)
            {
                if (BCrypt.Net.BCrypt.Verify(password, user.PasswordHash))
                {
                    return true;
                }
            }
        }
        return false;
    }
}

class Program
{
    static void Main(string[] args)
    {
        var userService = new UserService();

        // Đăng ký người dùng mới
        userService.RegisterUser("testuser", "password123", "testuser@example.com");
        Console.WriteLine("User registered.");

        // Đăng nhập người dùng
        bool loginSuccess = userService.LoginUser("testuser", "password123");
        Console.WriteLine(loginSuccess ? "Login successful." : "Login failed.");
    }
}

#Them,sua,xoa, truy van
using System;
using System.Collections.Generic;
using System.Linq;
using Microsoft.EntityFrameworkCore;

public class Book
{
    public int BookId { get; set; }
    public string Title { get; set; }
    public int AuthorId { get; set; }
    public Author Author { get; set; }
}

public class Author
{
    public int AuthorId { get; set; }
    public string Name { get; set; }
    public ICollection<Book> Books { get; set; }
}

public class AppDbContext : DbContext
{
    public DbSet<Book> Books { get; set; }
    public DbSet<Author> Authors { get; set; }

    protected override void OnConfiguring(DbContextOptionsBuilder optionsBuilder)
    {
        optionsBuilder.UseSqlServer(@"Server=.\SQLEXPRESS;Database=LibraryDb;Trusted_Connection=True;");
    }
}

public class LibraryService
{
    public void AddAuthorAndBook(string authorName, string bookTitle)
    {
        using (var context = new AppDbContext())
        {
            var author = new Author
            {
                Name = authorName
            };

            var book = new Book
            {
                Title = bookTitle,
                Author = author
            };

            context.Authors.Add(author);
            context.Books.Add(book);
            context.SaveChanges();
        }
    }

    public void UpdateBookTitle(int bookId, string newTitle)
    {
        using (var context = new AppDbContext())
        {
            var book = context.Books.Find(bookId);
            if (book != null)
            {
                book.Title = newTitle;
                context.SaveChanges();
            }
        }
    }

    public void UpdateAuthorName(int authorId, string newName)
    {
        using (var context = new AppDbContext())
        {
            var author = context.Authors.Find(authorId);
            if (author != null)
            {
                author.Name = newName;
                context.SaveChanges();
            }
        }
    }

    public void DeleteBook(int bookId)
    {
        using (var context = new AppDbContext())
        {
            var book = context.Books.Find(bookId);
            if (book != null)
            {
                context.Books.Remove(book);
                context.SaveChanges();
            }
        }
    }

    public void DeleteAuthor(int authorId)
    {
        using (var context = new AppDbContext())
        {
            var author = context.Authors.Find(authorId);
            if (author != null)
            {
                context.Authors.Remove(author);
                context.SaveChanges();
            }
        }
    }

    public void GetBooksWithAuthors()
    {
        using (var context = new AppDbContext())
        {
            var books = context.Books.Include(b => b.Author).ToList();
            foreach (var book in books)
            {
                Console.WriteLine($"{book.Title} by {book.Author.Name}");
            }
        }
    }
}

class Program
{
    static void Main(string[] args)
    {
        var libraryService = new LibraryService();

        // Thêm tác giả và sách mới
        libraryService.AddAuthorAndBook("George Orwell", "1984");
        libraryService.AddAuthorAndBook("F. Scott Fitzgerald", "The Great Gatsby");

        // Cập nhật tiêu đề sách
        libraryService.UpdateBookTitle(1, "Nineteen Eighty-Four");

        // Cập nhật tên tác giả
        libraryService.UpdateAuthorName(1, "Eric Arthur Blair");

        // Xóa sách
        libraryService.DeleteBook(2);

        // Xóa tác giả
        libraryService.DeleteAuthor(2);

        // Truy vấn sách kèm theo tác giả
        libraryService.GetBooksWithAuthors();
    }
}

