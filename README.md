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
