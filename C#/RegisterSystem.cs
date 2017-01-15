using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;
//using System;
using System.Text.RegularExpressions;

public class RegisterSystem : MonoBehaviour {

	public InputField userName;
	public InputField email;
	public InputField password;
	public InputField confirmPassword;

	private string _userName;
	private string _email;
	private string _password;
	private string _confirmPassword;

	private string form;
	private bool emailValid = false;
	private string uri = "URL to - regactsecure.php"; 	// Register Action Secure
	private string guri = "URL to - guestreg.php";		// Guest Register Secure.

	private List<string> randomSpecialKeys = new List<string>();

	public void Submit(){
		_userName = userName.text;
		_email = email.text;
		_password = password.text;
		_confirmPassword = confirmPassword.text;

		if (_userName == "" || _email == "" || _password == "" || _confirmPassword == "") {
			Debug.LogError ("One or more of the Input Fields are empty!");
			return;
		}

		if (_confirmPassword != _password) {
			Debug.LogError ("Confirm password isn't the same as Password!");
			return;
		}

		if (_userName != "" && _email != "" && _password == _confirmPassword && _confirmPassword == _password) {
			Debug.Log ("Attempting to register account, please standby...");
			//Register ();
			StartCoroutine(Register());
		}

	}


	IEnumerator Register(){
		// Now we attempt to register the account.
		WWWForm form = new WWWForm();
		form.AddField ("usernamePost",_userName);
		form.AddField ("emailPost",_email);
		form.AddField("passwordPost",_password);

		WWW www = new WWW (uri,form);

		yield return www;
		Debug.Log (www.text);
		if (www.text.Length > 1) {
			Debug.Log ("Account Already Exists - Attempting again!");
			_userName = "";
			_password = "";
			randomSpecialKeys.Clear ();
			GuestAccountGenerateInfo ();
		} else if (www.text.Length == 1) {
			Debug.Log ("SUCCESS");
			// So you don't have to type it in every time.
			PlayerPrefs.SetString ("un", _userName);
			PlayerPrefs.SetString ("pw", _password);
		}

	}



	public void GuestAccountGenerateInfo(){
		float id1 = Random.Range (0, 9999);
		float id2 = Random.Range (0, 9999);
		float id3 = Random.Range (0, 9999);
		float id4 = Random.Range (0, 9999);
		float pass1 = (Mathf.RoundToInt( Random.Range (0, 9999)));
		float pass2 = (Mathf.RoundToInt( Random.Range (0, 9999)));
		float pass3 = (Mathf.RoundToInt( Random.Range (0, 9999)));
		float pass4 = (Mathf.RoundToInt( Random.Range (0, 9999)));

		for (int i = 0; i < 10; i++) {
			int index = Random.Range (0, 5);
			if (index == 0) {
				randomSpecialKeys.Add ("!");
			}
			if (index == 1) {
				randomSpecialKeys.Add ("@");
			}
			if (index == 2) {
				randomSpecialKeys.Add ("#");
			}
			if (index == 3) {
				randomSpecialKeys.Add ("$");
			}
			if (index == 4) {
				randomSpecialKeys.Add ("%");
			}
		}
		string randomPassFilter = string.Concat (randomSpecialKeys.ToArray ());
		string finalPass = randomPassFilter + pass1.ToString() + pass2.ToString()+ randomPassFilter + pass3.ToString() + pass4.ToString();
		string finalID = id1.ToString() + id2.ToString() + id3.ToString() + id4.ToString();
		_userName = "Guest" + finalID;
		_password = finalPass.ToString();
		StartCoroutine(RegisterGuest());
	}


	//For Guest Accounts.
	IEnumerator RegisterGuest(){
		WWWForm form = new WWWForm();
		form.AddField ("usernamePost",_userName);
		form.AddField("passwordPost",_password);

		// Delete these two debugs before release of game (or if you feel like it).
		Debug.Log (_userName);
		Debug.Log (_password);

		WWW www = new WWW (guri,form);

		yield return www;
		Debug.Log (www.text);
		if (www.text.Length > 1) {
			Debug.Log ("Account Already Exists - Attempting again!");
			_userName = "";
			_password = "";
			randomSpecialKeys.Clear ();
			GuestAccountGenerateInfo ();
		} else if (www.text.Length == 1) {
			Debug.Log ("SUCCESS");
			// So you don't have to type it in every time.
			PlayerPrefs.SetString ("un", _userName);
			PlayerPrefs.SetString ("pw", _password);

			randomSpecialKeys.Clear ();
		}

	}
}
