/*
 * This file is NOT a part of Moodle - http://moodle.org/
 *
 * This client for Moodle 2 is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Originally created by Daryl Orwin at: https://moodle.org/mod/forum/discuss.php?d=210866#
 * Update by Morten Nilsen
 *
 */
using System;
using System.Collections.Generic;
using System.Net.Http;
using System.Threading.Tasks;
using Newtonsoft.Json;

namespace MoodleTest
{
	internal static class Program
	{
		private static void Main()
		{
			var user = new MoodleUser
			{
				username = "dorwin@fakeemail.com",
				password = "Pass@Fakew0rd",
				firstname = "Daryl",
				lastname = "Orwin",
				email = "dorwin@fakeemail.com"
			};
			try
			{
				var request = CreateUsers(new[] { user });
				request.Wait();
				var newUsers = request.Result;
			}
			catch (MoodleException moodleError)
			{
				// Handle errors
			}
		}

		private static async Task<List<MoodleCreateUserResponse>> CreateUsers(IEnumerable<MoodleUser> users)
		{
			// Build form data to post
			var i = 0;
			var postValues = new Dictionary<string, string>();
			foreach (var user in users)
			{
				postValues.Add($"users[{i}][username]", user.username);
				postValues.Add($"users[{i}][password]", user.password);
				postValues.Add($"users[{i}][firstname]", user.firstname);
				postValues.Add($"users[{i}][lastname]", user.lastname);
				postValues.Add($"users[{i}][email]", user.email);
				i++;
			}

			// Post form data to moodle
			var contents = await MoodlePost(postValues, "core_user_create_users");

			// Error
			if (contents.Contains("exception"))
				throw JsonConvert.DeserializeObject<MoodleException>(contents);

			// Good
			return JsonConvert.DeserializeObject<List<MoodleCreateUserResponse>>(contents);
		}

		private static async Task<string> MoodlePost(Dictionary<string, string> values, string function)
		{
			var token = "token-goes-here";

			var postData = new FormUrlEncodedContent(values);
			postData.Headers.Add("Accept", "application/json");

			var url = $"http://moodleurl.com/webservice/rest/server.php?wstoken={token}&wsfunction={function}&moodlewsrestformat=json";
			var response = await HttpClient.PostAsync(url, postData);
			return await response.Content.ReadAsStringAsync();
		}

		private static readonly HttpClient HttpClient = new HttpClient();
	}
	
	public class MoodleUser
	{
		public string username { get; set; }
		public string password { get; set; }
		public string firstname { get; set; }
		public string lastname { get; set; }
		public string email { get; set; }
	}

	public class MoodleException : Exception
	{
		public MoodleException(string exception, string errorcode, string message, string debuginfo)
			: base(message)
		{
			this.exception = exception;
			this.errorcode = errorcode;
			this.message = message;
			this.debuginfo = debuginfo;
		}

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
