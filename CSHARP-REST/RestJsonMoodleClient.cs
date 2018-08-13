/*
 * This file is NOT a part of Moodle - http://moodle.org/
 *
 * This client for Moodle 2 is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Originally created by Daryl Orwin at: https://moodle.org/mod/forum/discuss.php?d=210866#
 *
 */
using System;
using System.Collections.Generic;
using System.IO;
using System.Linq;
using System.Net;
using System.Text;
using System.Threading.Tasks;
using System.Runtime.Serialization.Json;
using System.Web.Script.Serialization;
using System.Web;
 
namespace MoodleTest
{
    class Program
    {
        static void Main(string[] args)
        {
            String token = "token-goes-here";
 
            MoodleUser user = new MoodleUser();
            user.username = HttpUtility.UrlEncode("dorwin@fakeemail.com");
            user.password = HttpUtility.UrlEncode("Pass@Fakew0rd");
            user.firstname = HttpUtility.UrlEncode("Daryl");
            user.lastname = HttpUtility.UrlEncode("Orwin");
            user.email = HttpUtility.UrlEncode("dorwin@fakeemail.com");
 
            List<MoodleUser> userList = new List<MoodleUser>();
            userList.Add(user);
 
            Array arrUsers = userList.ToArray();
 
            String postData = String.Format("users[0][username]={0}&users[0][password]={1}&users[0][firstname]={2}&users[0][lastname]={3}&users[0][email]={4}", user.username, user.password, user.firstname, user.lastname, user.email);
 
            
 
            string createRequest = string.Format("http://moodleurl.com/webservice/rest/server.php?wstoken={0}&wsfunction={1}&moodlewsrestformat=json", token, "core_user_create_users");
 
            // Call Moodle REST Service
            HttpWebRequest req = (HttpWebRequest)WebRequest.Create(createRequest);
            req.Method = "POST";
            req.ContentType = "application/x-www-form-urlencoded";
 
            // Encode the parameters as form data:
            byte[] formData =
                UTF8Encoding.UTF8.GetBytes(postData);
            req.ContentLength = formData.Length;
 
            // Write out the form Data to the request:
            using (Stream post = req.GetRequestStream())
            {
                post.Write(formData, 0, formData.Length);
            }           
 
 
            // Get the Response
            HttpWebResponse resp = (HttpWebResponse)req.GetResponse();
            Stream resStream = resp.GetResponseStream();
            StreamReader reader = new StreamReader(resStream);
            string contents = reader.ReadToEnd();
 
            // Deserialize
            JavaScriptSerializer serializer = new JavaScriptSerializer();
            if (contents.Contains("exception"))
            {
                // Error
                MoodleException moodleError = serializer.Deserialize<MoodleException>(contents);
            }
            else
            {
                // Good
                List<MoodleCreateUserResponse> newUsers = serializer.Deserialize<List<MoodleCreateUserResponse>>(contents);
            }
           
        }
 
        public class MoodleUser
        {
            public string username { get; set; }
            public string password { get; set; }
            public string firstname { get; set; }
            public string lastname { get; set; }
            public string email { get; set; }
        }
 
        public class MoodleException
        {
            public string exception { get; set; }
            public string errorcode { get; set; }
            public string message { get; set; }
            public string debuginfo { get; set; }
        }
 
        public class MoodleCreateUserResponse
        {
            public string id { get; set; }
            public string username { get; set; }
        }
    }
}